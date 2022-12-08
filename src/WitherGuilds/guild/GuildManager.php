<?php

namespace WitherGuilds\guild;

use pocketmine\block\Transparent;
use pocketmine\world\Position;
use WitherGuilds\user\User;
use WitherGuilds\utils\ConfigUtil;

class GuildManager {

    /** @var Guild[]  */
    private array $guilds = [];

    public function createGuild(string $tag, string $name, User $leader): void {
        $player = $leader->getPlayer();

        if ($player === null)
            return;

        $leaderPosition = $player->getPosition();

        $arm = floor(ConfigUtil::DEFAULT_SIZE / 2);

        $heartPosition = new Position($leaderPosition->getFloorX(), ConfigUtil::HEART_Y, $leaderPosition->getFloorZ(), $leaderPosition->getWorld());
        $position1 = new Position($leaderPosition->getFloorX() + $arm, 0, $leaderPosition->getFloorZ() + $arm, $player->getWorld());
        $position2 = new Position($leaderPosition->getFloorX() - $arm, 0, $leaderPosition->getFloorZ() - $arm, $player->getWorld());

        $guild = new Guild($tag, $name, $leader, $position1, $position2, $heartPosition, ConfigUtil::DEFAULT_SIZE);

        $leader->setGuild($guild);
        
        $this->guilds[] = $guild;
    }


    public function guildExists(string $tag): bool {
        foreach ($this->guilds as $guild) {
            if ($guild->getTag() === $tag)
                return true;
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

}