<?php

namespace WitherGuilds\user;

class UserManager {

    /** @var User[]  */
    private array $users = [];

    public function createUser(string $nick): void {
        $this->users[] = new User($nick, null);
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