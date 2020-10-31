<?php


namespace App\Models;


use App\Events\{DayEnd, DayStart};
use App\Traits\LoggerTrait;
use InvalidArgumentException;
use SplObserver;
use SplSubject;

final class Flower implements SplObserver {

    use LoggerTrait;

    private const MAX_FEED_COUNT = 10;

    private int $feedCount = 0;

    private int $id;

    private bool $open = false;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function update(SplSubject $event) {
        switch (true) {
            case $event instanceof DayStart:
                $this->onDayStart();
                break;
            case $event instanceof DayEnd:
                $this->onDayEnd();
                break;
            default:
                throw new InvalidArgumentException('Event not supported');
        }
    }

    private function onDayStart(): void {
        $this->setOpen(true);
    }

    private function setOpen(bool $isOpen): void {
        $this->open = $isOpen;
    }

    private function onDayEnd(): void {
        $this->setOpen(false);
    }

    public function feedOn(): bool {
        $result = true;
        if (!$this->isFedOut()) {
            $this->addFeedCount();
        } else {
            $result = false;
        }

        $this->logger->info(sprintf('FLOWER-%s (%s)', $this->id, self::MAX_FEED_COUNT - $this->feedCount));

        return $result;
    }

    public function isOpen(): bool {
        return $this->open;
    }

    private function isFedOut(): bool {
        return $this->feedCount >= self::MAX_FEED_COUNT;
    }

    private function addFeedCount(): void {
        $this->feedCount++;
    }
}
