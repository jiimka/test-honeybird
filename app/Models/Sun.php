<?php


namespace App\Models;


final class Sun {

    private int $hours = 0;

    public function addHour(): void {
        $this->hours++;
    }

    public function totalHours(): int {
        return $this->hours;
    }
}
