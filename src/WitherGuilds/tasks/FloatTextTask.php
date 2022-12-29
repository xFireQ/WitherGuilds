<?php

namespace WitherGuilds\tasks;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\particle\FloatingTextParticle;
use WitherGuilds\guild\Guild;
use WitherGuilds\Main;
use WitherGuilds\utils\ChatUtil;
use WitherGuilds\utils\ConfigUtil;

class FloatTextTask extends Task {

    private FloatingTextParticle $text;

    public function __construct(private Guild $guild){
        $this->update($this->guild, true);
    }

    public function onRun(): void{
        $world = Server::getInstance()->getWorldManager()->getDefaultWorld();
        $pos = $this->guild->getHeartPosition();

        if($this->guild === null) {
            $this->text->setInvisible(true);
            $world->addParticle($pos->add(0.5, 1.5, 0.5), $this->text);
            $this->getHandler()->cancel();
            return;
        }

        $this->update($this->guild);
        $world->addParticle($pos->add(0.5, 1.5, 0.5), $this->text);
    }

    private function update(Guild $guild, bool $new = false): void {
        $days = 0;
        $expiryH = 0;
        $expiryM = 0;
        $expiryS = 0;

        if(!$guild->canExpiry()) {
            $exipiryTime = strtotime($guild->getExpiryDate()) - time();
            $days = intval(intval($exipiryTime) / (3600*24));
            $expiryH = floor($exipiryTime / 3600 % 24);
            $expiryM = floor(($exipiryTime / 60) % 60);
            $expiryS = $exipiryTime % 60;
        }

        $adays = 0;
        $aH = 0;
        $aM = 0;
        $aS = 0;

        if(!$guild->canConquer()) {
            $aTime = strtotime($guild->getConquerDate()) - time();
            $adays = intval(intval($aTime) / (3600*24));
            $aH = floor($aTime / 3600 % 24);
            $aM = floor(($aTime / 60) % 60);
            $aS = $aTime % 60;
        }

        if ($new) {
            $this->text = new FloatingTextParticle(
                ChatUtil::fixColors(
                    "&r&8[&6".$this->guild->getTag()."&r&8] - &6".$this->guild->getName().
                    "\n&r&fWygasa za: &6{$days}&fdni &6{$expiryH}&fh &6{$expiryM}&fm &6{$expiryS}&fs\n&r&fOchrona: &6{$adays}&fdni &6{$aH}&fh &6{$aM}&fm &6{$aS}&fs\n&r&fZycia: &6{$guild->getHp()}&f/&6".ConfigUtil::MAX_HP));
        } else {
            $this->text->setText(
                ChatUtil::fixColors(
                    "&r&8[&6".$this->guild->getTag()."&r&8] - &6".$this->guild->getName().
                    "\n&r&fWygasa za: &6{$days}&fdni &6{$expiryH}&fh &6{$expiryM}&fm &6{$expiryS}&fs\n&r&fOchrona: &6{$adays}&fdni &6{$aH}&fh &6{$aM}&fm &6{$aS}&fs\n&r&fZycia: &6{$guild->getHp()}&f/&6".ConfigUtil::MAX_HP));
        }



    }
}