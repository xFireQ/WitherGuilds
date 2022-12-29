<?php

namespace WitherGuilds\form;

use \pocketmine\form\Form as iForm;
use pocketmine\player\Player;

class Form implements iForm{

    protected array $data = [];

    public function handleResponse(Player $player, $data): void {

    }

    public function jsonSerialize(): mixed {
        return $this->data;
    }


}