<?php

namespace WitherGuilds\user;

use WitherGuilds\guild\Guild;

class User {

    public function __construct(private string $name, private ?Guild $guild = null) {
    }

    /** @param Guild|null $guild */
    public function setGuild(?Guild $guild): void{
        $this->guild = $guild;
    }

    /** @return string */
    public function getName(): string{
        return $this->name;
    }

    /** @return ?Guild */
    public function getGuild(): ?Guild{
        return $this->guild;
    }
}