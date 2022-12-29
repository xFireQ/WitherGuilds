<?php

namespace WitherGuilds\form;

use pocketmine\item\Item;
use pocketmine\player\Player;
use WitherGuilds\utils\ChatUtil;
use WitherGuilds\utils\ConfigUtil;

class ItemsForm extends Form {
    public function __construct() {
        $data = [
            "type" => "form",
            "content" => "",
            "title" => "ITEMY NA GILDIE",
            "buttons" => []
        ];

        foreach (ConfigUtil::getGuildItems() as $guildItem) {
            $data["buttons"][] = ["text" => ChatUtil::fixColors("&6".$guildItem->getName()." x".$guildItem->getCount())];
        }

        $this->data = $data;
    }

    public function handleResponse(Player $player, $data): void {

    }
}