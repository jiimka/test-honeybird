<?php

namespace Tests\Unit;

use App\Events\{DayEnd, DayStart};
use App\Log\AbstractLogger;
use App\Models\Flower;
use PHPUnit\Framework\TestCase;

class FlowerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsOpenedOnDayStart(): void {
        $flower = new Flower(1);
        $flower->update(new DayStart());

        self::assertTrue($flower->isOpen());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsOpenedOnDayEnd(): void {
        $flower = new Flower(1);
        $flower->update(new DayEnd());

        self::assertNotTrue($flower->isOpen());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConsoleOutputOnFeed(): void {
        $flower = new Flower(1);
        $flower->setLogger(AbstractLogger::get());

        $this->expectOutputString("FLOWER-1 (9)\n");
        self::assertTrue($flower->feedOn());
    }
}
