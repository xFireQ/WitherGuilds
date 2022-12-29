<?php

namespace WitherGuilds\tasks;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use WitherGuilds\user\UserManager;
use WitherGuilds\utils\ChatUtil;

class BaseTeleportTask extends Task {

    private int $time = 10;

    public function __construct(private Position $position, private Player $player) { }


    public function onRun(): void {
        $this->time--;

        $player = $this->player;
        $pos = $this->position;

        if ($this->time < 1) {
            $player->teleport($pos);
            $player->sendTip(ChatUtil::format("Teleportowano do bazy gildii!"));
            unset(UserManager::$teleportTasks[$player->getName()]);
            $this->getHandler()->cancel();
            return;
        } else {
            $player->sendTip(ChatUtil::fixColors("&fTeleportacja za: &6".$this->time." &fsekund!"));
        }
    }
}