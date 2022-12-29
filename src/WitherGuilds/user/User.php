<?php

namespace WitherGuilds\user;

use pocketmine\player\Player;
use pocketmine\Server;
use WitherGuilds\api\bossbar\BossBar;
use WitherGuilds\guild\Guild;

class User {

    public function __construct(
        private string $name,
        private string $xuid,
        private ?Guild $guild = null,
        private BossBar $bar,
        private bool $enter = false) {
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

    public function getPlayer(): ?Player {
        return Server::getInstance()->getPlayerExact($this->name);
    }

    public function isEnter(): bool {
        return $this->enter;
    }

    public function setEnter(bool $enter): void{
        $this->enter = $enter;
    }

    public function getBar(): BossBar{
        return $this->bar;
    }

    /**
     * @return string
     */
    public function getXuid(): string
    {
        return $this->xuid;
    }
}