<?php

namespace WitherGuilds;

use pocketmine\plugin\PluginBase;
use WitherGuilds\api\bossbar\PacketListener;
use WitherGuilds\commands\CreateCommand;
use WitherGuilds\commands\DeleteCommand;
use WitherGuilds\commands\FfCommand;
use WitherGuilds\commands\InfoCommand;
use WitherGuilds\commands\InvitePlayerCommand;
use WitherGuilds\commands\JoinCommand;
use WitherGuilds\commands\KickCommand;
use WitherGuilds\commands\QuitCommand;
use WitherGuilds\guild\GuildManager;
use WitherGuilds\listeners\block\BlockBreakListener;
use WitherGuilds\listeners\block\BlockPlaceListener;
use WitherGuilds\listeners\player\PlayerInteractListener;
use WitherGuilds\listeners\entity\EntityDamageListener;
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
            new DeleteCommand(),
            new InvitePlayerCommand(),
            new QuitCommand(),
            new JoinCommand(),
            new FfCommand(),
            new KickCommand()
        ];

        foreach ($commands as $command)
            $commandMap->register("WitherCommands", $command);
    }

    private function registerEvents(): void {
        $pluginManager = $this->getServer()->getPluginManager();

        $listeners = [
            new PlayerJoinListener(),
            new PlayerMoveListener(),
            new PlayerInteractListener(),

            new BlockBreakListener(),
            new BlockPlaceListener(),

            new EntityDamageListener()
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