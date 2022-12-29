<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;

class GuildHelpCommand extends Command {

    public function __construct(){
        parent::__construct("ghelp", "Pomoc gildii", null, ["g"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $help = [
            "zaloz" => "zaklada gildie",
            "baza" => "teleportuje do bazy gildii",
            "ustawbaze" => "ustawia baze gildii",
            "info" => "pokazuje informacje o danej gildii",
            "wyrzuc" => "wyrzuca gracza z gildii",
            "zapros" => "zaprasza gracza do gildii",
            "pvp" => "wlacza lub wylacza pvp w gildii",
            "opusc" => "opuszcza gildie",
            "leader" => "oddaje lidera",
            "usun" => "usuwa gildie",
            "powieksz" => "powieksza gildie",
            "przedluz" => "przedluza waznosc gildii"
        ];

        $helpStr = "";

        foreach ($help as $command => $desc)
            $helpStr .= "&8/&6".$command." &8- &f".$desc."\n";

        $sender->sendMessage(ChatUtil::fixColors("&r&6&lKOMENDY GILDII:&r \n\n".$helpStr."\n"));
    }
}