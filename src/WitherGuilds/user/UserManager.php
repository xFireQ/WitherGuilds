<?php

namespace WitherGuilds\user;

use WitherGuilds\api\bossbar\BossBar;
use WitherGuilds\database\DbManager;

class UserManager {

    /** @var User[]  */
    private array $users = [];

    public static array $teleportTasks = [];
    public static array $invitationUsers = [];

    public function createUser(string $nick, string $xuid): void {
        $this->users[] = new User($nick, $xuid, null, new BossBar());
    }

    public function saveUsers(): void {
        $db = DbManager::getDb();

        foreach ($this->users as $user) {
            $userName = $user->getName();
            $userXuid = $user->getXuid();

            $db->query("INSERT INTO 'users' (name, xuid) VALUES ('$userName', '$userXuid')");
        }
    }

    public function loadUsers(): void {
        $db = DbManager::getDb();
        $dbUsers = DbManager::getDb()->query("SELECT * FROM 'users'");
        $users = [];

        while ($row = $dbUsers->fetchArray()) {
            $users[] = new User($row["name"], $row["xuid"], null, new BossBar());
        }

        $this->users = $users;
    }

    public function getUser(string $nick): ?User {
        foreach ($this->users as $user) {
            if ($nick === $user->getName())
                return $user;
        }

        return null;
    }

    /** @return User[] */
    public function getUsers(): array{
        return $this->users;
    }
}