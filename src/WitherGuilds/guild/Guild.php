<?php

namespace WitherGuilds\guild;

use WitherGuilds\user\User;

class Guild {
    public function __construct(
        private string $tag,
        private string $name,
        private User $leader
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return User
     */
    public function getLeader(): User
    {
        return $this->leader;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }
}