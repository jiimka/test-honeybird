<?php


namespace App\Models;


use App\Traits\LoggerTrait;
use App\Events\{DayEnd, DayStart, HourChange};
use App\Exceptions\NotFoundException;
use InvalidArgumentException;
use SplObserver;
use SplSubject;

final class Honeybird implements SplObserver {
    use LoggerTrait;

    private ?FlowersSet $flowersSet;

    private bool $sleeping = true;

    public function setFlowersSet(FlowersSet $flowersSet): void {
        $this->flowersSet = $flowersSet;
    }

    /**
     * @param \SplSubject $event
     *
     * @throws \Exception
     */
    public function update(SplSubject $event) {
        switch (true) {
            case $event instanceof DayStart:
                $this->onDayStart();
                break;
            case $event instanceof DayEnd:
                $this->onDayEnd();
                break;
            case $event instanceof HourChange:
                $this->onHourChange();
                break;
            default:
                throw new InvalidArgumentException('Event not supported');
        }
    }

    private function onDayStart(): void {
        $this->wake();
    }

    private function wake(): void {
        $this->setSleeping(false);
    }

    private function setSleeping(bool $sleeping): void {
        $this->sleeping = $sleeping;
    }

    private function onDayEnd(): void {
        $this->fallAsleep();
    }

    private function fallAsleep(): void {
        $this->setSleeping(true);
    }

    /**
     * @throws \Exception
     */
    private function onHourChange(): void {
        if ($this->sleeping) {
            $this->sleep();
        } else {
            $this->feed();
        }
    }

    public function sleep(): void {
        $this->logger->info("SLEEP");
    }

    /**
     * @throws \App\Exceptions\NotFoundException
     */
    public function feed(): void {
        $success = false;
        if ($this->flowersSet) {
            $flowers = $this->flowersSet->getFlowers();
            while ($flowers) {
                $randomFlowerIndex = array_rand($flowers);
                $flower = $flowers[$randomFlowerIndex];
                if ($flower->isOpen() && $flower->feedOn()) {
                    $success = true;
                    break;
                }

                unset($flowers[$randomFlowerIndex]);
            }
        }

        if (!$success) {
            throw new NotFoundException('');
        }
    }
}
