<?php

namespace WitherGuilds;

use pocketmine\plugin\PluginBase;
use WitherGuilds\api\bossbar\PacketListener;
use WitherGuilds\commands\CreateCommand;
use WitherGuilds\commands\DeleteCommand;
use WitherGuilds\commands\InfoCommand;
use WitherGuilds\guild\GuildManager;
use WitherGuilds\listeners\block\BlockBreakListener;
use WitherGuilds\listeners\player\PlayerJoinListener;
use WitherGuilds\listeners\player\PlayerMoveListener;
use WitherGuilds\user\UserManager;

class Main extends PluginBase {

    private static Main $instance;
    private UserManager $userManager;
    private GuildManager $guildManager;

    /** @return Main */
    public static function getInstance(): Main{
        return self::$instance;
    }

    public function onEnable(): void {
        self::$instance = $this;

        $this->userManager = new UserManager();
        $this->guildManager = new GuildManager();

        $this->registerEvents();
        $this->registerCommands();
    }

    private function registerCommands() : void {
        $commandMap = $this->getServer()->getCommandMap();

        $commands = [
            new CreateCommand(),
            new InfoCommand(),
            new DeleteCommand()
        ];

        foreach ($commands as $command)
            $commandMap->register("WitherCommands", $command);
    }

    private function registerEvents(): void {
        $pluginManager = $this->getServer()->getPluginManager();

        $listeners = [
            new PlayerJoinListener(),
            new PlayerMoveListener(),
            new BlockBreakListener()
        ];

        foreach ($listeners as $listener) {
            $pluginManager->registerEvents($listener, $this);
        }
    }

    /** @return UserManager*/
    public function getUserManager(): UserManager {
        return $this->userManager;
    }

    /** @return GuildManager */
    public function getGuildManager(): GuildManager {
        return $this->guildManager;
    }
}