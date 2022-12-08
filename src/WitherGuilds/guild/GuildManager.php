<?php

namespace WitherGuilds\guild;

use WitherGuilds\user\User;

class GuildManager {

    /** @var Guild[]  */
    private array $guilds = [];

    public function createGuild(string $tag, string $name, User $leader): void {
        $guild = new Guild($tag, $name, $leader);

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

    /**
     * @return Guild[]
     */
    public function getGuilds(): array
    {
        return $this->guilds;
    }

}