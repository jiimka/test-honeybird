<?php

namespace App;

use App\Traits\LoggerTrait;
use App\Events\{DayEnd, DayStart, Event, HourChange};
use App\Log\AbstractLogger;
use App\Models\{FlowersSet, Honeybird, Sun};
use Exception;

final class App {
    use LoggerTrait;

    private const DAYS_TILL_THE_END_OF_THE_WORLD = 2020; // :)

    private const HOURS_IN_DAY = 24;

    private Sun $sun;

    private FlowersSet $flowersSet;

    private Honeybird $honeybird;

    public function run(array $args = []): void {
        $this->setLogger(AbstractLogger::get($args['logger'] ?? null));
        $this->setModels();
        $this->logger->begin();
        $currentDay = 0;
        while ($currentDay < self::DAYS_TILL_THE_END_OF_THE_WORLD) {
            $this->nextDay();
            $currentDay++;
        }

        $this->logger->end();
    }

    private function setModels(): void {
        $this->sun = new Sun();
        $this->flowersSet = new FlowersSet(10, $this->logger);
        $this->honeybird = new Honeybird();
        $this->honeybird->setLogger($this->logger);
        $this->honeybird->setFlowersSet($this->flowersSet);
    }

    private function nextDay(): void {
        for ($hours = 0; $hours < self::HOURS_IN_DAY; $hours++) {
            if ($hours === 0) {
                $this->startDay($hours);
            } elseif ($hours === self::HOURS_IN_DAY / 2) {
                $this->endDay($hours);
            }

            try {
                $this->changeHour($hours);
            } catch (Exception $exception) {
                $this->logger->info(sprintf('EXIT (%s)', $this->sun->totalHours()));
                die();
            }

            $this->sun->addHour();
        }
    }

    private function startDay(int $hours): void {
        $this->logger->info(sprintf('DAY START (%s)', $hours));
        $this->dispatchDayEvent(new DayStart());
    }

    private function dispatchDayEvent(Event $event): void {
        $event->attach($this->honeybird);
        foreach ($this->flowersSet->getFlowers() as $flower) {
            $event->attach($flower);
        }

        $event->notify();
    }

    private function endDay(int $hours): void {
        $this->logger->info(sprintf('DAY END (%s)', $hours));
        $this->dispatchDayEvent(new DayEnd());
    }

    private function changeHour($hours): void {
        $this->logger->info(sprintf('HOUR CHANGE (%s)', $hours));
        $this->dispatchHourChangeEvent();
    }

    private function dispatchHourChangeEvent(): void {
        $event = new HourChange();
        $event->attach($this->honeybird);
        $event->notify();
    }
}
