<?php

namespace WitherGuilds\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use WitherGuilds\form\ItemsForm;

class ItemsCommand extends Command {

    public function __construct(){
        parent::__construct("items", "Pokazuje itemy na gildie", null, ["itemy"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $sender->sendForm(new ItemsForm($sender));
    }
}