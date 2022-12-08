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
        private int $currentSize
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

    public function getMaxPosition1(): Position {
        return $this->maxPosition1;
    }

    public function getMaxPosition2(): Position {
        return $this->maxPosition2;
    }

    public function isInPlot(Position $position, bool $max = false) : bool {
        if($max) {
            $position1 = $this->maxPosition1;
            $position2 = $this->maxPosition2;
        } else {
            $position1 = $this->position1;
            $position2 = $this->position2;
        }

        return $position->getFloorX() <= max($position1->getFloorX(), $position2->getFloorX()) &&
            $position->getFloorX() >= min($position1->getFloorX(), $position2->getFloorX()) &&
            $position->getFloorZ() <= max($position1->getFloorZ(), $position2->getFloorZ()) &&
            $position->getFloorZ() >= min($position1->getFloorZ(), $position2->getFloorZ());
    }
}