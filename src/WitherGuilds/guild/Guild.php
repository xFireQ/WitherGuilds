<?php

namespace WitherGuilds\guild;

use pocketmine\world\Position;
use WitherGuilds\user\User;

class Guild {
    public function __construct(
        private string $tag,
        private string $name,
        private User $leader,
        private Position $position1,
        private Position $position2,
        private Position $heartPosition,
        private int $currentSize,
        private array $members,
        private bool $pvp,
        private Position $basePosition,
        private int $hp,
        private string $expiryDate,
        private string $conquerDate,
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getLeader(): User {
        return $this->leader;
    }

    public function getTag(): string {
        return $this->tag;
    }

    public function getPosition1(): Position {
        return $this->position1;
    }

    public function getPosition2(): Position {
        return $this->position2;
    }

    public function getCurrentSize(): int{
        return $this->currentSize;
    }

    public function getHeartPosition(): Position {
        return $this->heartPosition;
    }

    public function getBasePosition(): Position{
        return $this->basePosition;
    }

    public function getMaxPosition1(): Position {
        return $this->maxPosition1;
    }

    public function getMaxPosition2(): Position {
        return $this->maxPosition2;
    }

    public function getMembers(): array{
        return $this->members;
    }

    public function getHp(): int {
        return $this->hp;
    }


    public function isEnablePvp(): bool {
        return $this->pvp;
    }

    public function isInPlot(Position $position) : bool {
        $position1 = $this->position1;
        $position2 = $this->position2;

        return $position->getFloorX() <= max($position1->getFloorX(), $position2->getFloorX()) &&
            $position->getFloorX() >= min($position1->getFloorX(), $position2->getFloorX()) &&
            $position->getFloorZ() <= max($position1->getFloorZ(), $position2->getFloorZ()) &&
            $position->getFloorZ() >= min($position1->getFloorZ(), $position2->getFloorZ());
    }

    public function addMember(string $nick) {
        $this->members[] = $nick;
    }

    public function removeMember(string $nick) {
        foreach ($this->members as $index => $member) {
            if ($nick === $member)
                unset($this->members[$index]);
        }
    }

    public function setPvp(bool $pvp): void{
        $this->pvp = $pvp;
    }

    public function setBasePosition(Position $basePosition): void{
        $this->basePosition = $basePosition;
    }

    public function setHP(int $hp): void{
        $this->hp = $hp;
    }

    public function getExpiryDate() : string {
        return $this->expiryDate;
    }

    public function setExpiryDate(string $date) : void {
        $this->expiryDate = $date;
    }

    public function getConquerDate() : string {
        return $this->conquerDate;
    }

    public function setConquerDate(string $date) : void {
        $this->conquerDate = $date;
    }

    public function canConquer() : bool {
        return time() > strtotime($this->getConquerDate());
    }

    public function canExpiry() : bool {
        return time() > strtotime($this->getExpiryDate());
    }

    public function setPosition1(Position $position1): void{
        $this->position1 = $position1;
    }

    public function setPosition2(Position $position2): void{
        $this->position2 = $position2;
    }

    public function setCurrentSize(int $currentSize): void{
        $this->currentSize = $currentSize;
    }


}