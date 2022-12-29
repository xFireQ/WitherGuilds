<?php

namespace WitherGuilds\guild;

use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\Position;
use WitherGuilds\database\DbManager;
use WitherGuilds\Main;
use WitherGuilds\tasks\FloatTextTask;
use WitherGuilds\user\User;
use WitherGuilds\utils\ConfigUtil;
use WitherGuilds\utils\ShapesUtil;

class GuildManager {

    /** @var Guild[]  */
    private array $guilds = [];
    public static array $wars = [];

    public function createGuild(string $tag, string $name, User $leader): void {
        $player = $leader->getPlayer();

        if ($player === null)
            return;


        $leaderPosition = $player->getPosition();

        $arm = floor(ConfigUtil::DEFAULT_SIZE / 2);

        $heartPosition = new Position($leaderPosition->getFloorX(), ConfigUtil::HEART_Y, $leaderPosition->getFloorZ(), $leaderPosition->getWorld());
        $position1 = new Position($leaderPosition->getFloorX() + $arm, 0, $leaderPosition->getFloorZ() + $arm, $player->getWorld());
        $position2 = new Position($leaderPosition->getFloorX() - $arm, 0, $leaderPosition->getFloorZ() - $arm, $player->getWorld());

        $date = date_create(date("G:i:s"));
        date_add($date,date_interval_create_from_date_string("3 days"));
        $date =  date_format($date,"d.m.Y H:i:s");
        $adate = date_create(date("G:i:s"));
        date_add($adate,date_interval_create_from_date_string("1 days"));
        $adate = date_format($adate,"d.m.Y H:i:s");

        $guild = new Guild($tag, $name, $leader, $position1, $position2, $heartPosition, ConfigUtil::DEFAULT_SIZE, [$leader->getName()], false, $heartPosition, ConfigUtil::START_HP, $date, $adate);

        $leader->setGuild($guild);
        $this->guilds[] = $guild;
        ShapesUtil::create($heartPosition);

        $player->teleport($heartPosition);
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new FloatTextTask($guild), 20*5);

    }

    public function save(): void {
        $db = DbManager::getDb();

        foreach ($this->guilds as $guild) {
            $tag = $guild->getTag();
            $name = $guild->getName();
            $leader = $guild->getLeader()->getName();
            $x1 = $guild->getPosition1()->getFloorX();
            $z1 = $guild->getPosition1()->getFloorZ();
            $x2 = $guild->getPosition2()->getFloorX();
            $z2 = $guild->getPosition2()->getFloorZ();
            $heartX = $guild->getHeartPosition()->getFloorX();
            $heartZ = $guild->getHeartPosition()->getFloorZ();
            $baseX = $guild->getBasePosition()->getFloorX();
            $baseY = $guild->getBasePosition()->getFloorY();
            $baseZ = $guild->getBasePosition()->getFloorZ();
            $currentSize = $guild->getCurrentSize();
            $pvp = $guild->isEnablePvp();
            $hp = $guild->getHp();
            $eDate = $guild->getExpiryDate();
            $cDate = $guild->getConquerDate();

            if (!empty($db->query("SELECT * FROM 'guilds' WHERE tag = '$tag'")))
                $db->query("DELETE FROM 'guilds' WHERE tag = '$tag'");

            $sql = "INSERT INTO 'guilds' (tag, name, leader, x1, z1, x2, z2, heartX, heartZ, baseX, baseY, baseZ, currentSize, PVP, hp, eDate, cDate) VALUES ('$tag', '$name', '$leader', '$x1', '$z1', '$x2', '$z2', '$heartX', '$heartZ', '$baseX', '$baseY', '$baseZ', '$currentSize', '$pvp', '$hp', '$eDate', '$cDate')";
            $db->query($sql);
        }

        foreach ($this->guilds as $g) {
            $tag = $g->getTag();

            foreach ($g->getMembers() as $member) {
                $rank = "Czlonek";

                if ($g->getLeader() === $member)
                    $rank = "Lider";

                if (empty($db->query("SELECT * FROM 'gMembers' WHERE member = '$member' AND guild = '$tag'")->fetchArray())) {
                    $db->query("INSERT INTO 'gMembers' (member, guild, rank) VALUES ('$member', '$tag', '$rank')");
                } else {
                    $db->query("UPDATE 'gMembers' SET member = '$member', guild = '$tag', rank = '$rank'");
                }
            }
        }
    }

    public function load(): void {
        $db = DbManager::getDb();
        $dbG = DbManager::getDb()->query("SELECT * FROM 'guilds'");
        $memberDb = DbManager::getDb()->query("SELECT * FROM 'gMembers'");

        while ($row = $dbG->fetchArray()) {
            $arm = floor($row["currentSize"] / 2);
            $world = Server::getInstance()->getWorldManager()->getDefaultWorld();

            $heartPosition = new Position($row["heartX"], ConfigUtil::HEART_Y, $row["heartZ"], $world);
            $position1 = new Position($row["x1"], 0, $row["z1"], $world);
            $position2 = new Position($row["x2"], 0, $row["z2"], $world);
            $userLeader = Main::getInstance()->getUserManager()->getUser($row["leader"]);

            $guild = new Guild($row["tag"], $row["name"], $userLeader, $position1, $position2, $heartPosition, $row["currentSize"], [$row["leader"]], $row["PVP"], $heartPosition, $row["hp"], $row["eDate"], $row["cDate"]);

            $userLeader->setGuild($guild);
            $this->guilds[] = $guild;
        }

        while ($row = $memberDb->fetchArray(SQLITE3_ASSOC)) {
            $guild = Main::getInstance()->getGuildManager()->getGuildByTag($row["guild"]);

            if ($guild !== null) {
                $guild->addMember($row["member"]);
                Main::getInstance()->getUserManager()->getUser($row["member"])->setGuild($guild);
            }
        }

        foreach ($this->guilds as $g) {
            Main::getInstance()->getScheduler()->scheduleRepeatingTask(new FloatTextTask($g), 20*5);

        }
    }


    public function guildExists(string $tag): bool {
        foreach ($this->guilds as $guild) {
            if ($guild->getTag() === $tag)
                return true;
        }
        return false;
    }

    public function isHeart(Block $block): bool {
        $position = $block->getPosition();

        if ($block->getId() === ConfigUtil::HEART_ID && $block->getMeta() === ConfigUtil::HEART_META) {
            foreach ($this->guilds as $guild) {
                $heartPosition = $guild->getHeartPosition();
                if ($heartPosition->getFloorX() === $position->getFloorX() &&
                    $heartPosition->getFloorY() === $position->getFloorY() &&
                    $heartPosition->getFloorZ() === $position->getFloorZ()
                ) {
                    return true;
                }
            }
        }
        return false;
    }


    public function guildExistsAtPosition(Position $position): bool {
        foreach ($this->guilds as $guild)
            if ($guild->isInPlot($position))
                return true;

        return false;
    }

    public function getGuildByTag(string $tag): ?Guild {
        foreach ($this->guilds as $guild) {
            if ($guild->getTag() === $tag)
                return $guild;
        }

        return null;
    }

    public function deleteGuild(Guild $deleteGuild) : void {
        foreach ($this->guilds as $index => $guild) {
            if ($guild->getTag() === $deleteGuild->getTag())
                unset($this->guilds[$index]);
        }
    }

    public function getGuildAtPosition(Position $position) : ?Guild {
        foreach ($this->guilds as $guild) {
            if ($guild->isInPlot($position))
                return $guild;
        }

        return null;
    }


    public function isInOwnPlot(Position $position, User $user) : bool {
        $guild = $this->getGuildAtPosition($position);

        if ($guild === null)
            return true;

        if ($user->getGuild() === null)
            return false;

        return $user->getGuild()->getTag() === $guild->getTag();
    }

    public function getGuilds(): array{
        return $this->guilds;
    }

    public function addPlotGuild(Guild $guild, int $size): void {
        $maxSize = 160;
        $world = Server::getInstance()->getWorldManager()->getDefaultWorld();

        $x = $guild->getHeartPosition()->getFloorX();
        $z = $guild->getHeartPosition()->getFloorZ();

        $arm = floor($size / 2);
        $x1 = $x + $arm;
        $z1 = $z + $arm;
        $x2 = $x - $arm;
        $z2 = $z - $arm;

        $pos1 = new Position($x1, 0, $z1, $world);
        $pos2 = new Position($x2, 0, $z2, $world);

        $guild->setPosition1($pos1);
        $guild->setPosition2($pos2);

    }

}