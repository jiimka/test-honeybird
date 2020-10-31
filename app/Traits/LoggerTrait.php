<?php


namespace App\Traits;


use App\Log\AbstractLogger;

trait LoggerTrait {
    private AbstractLogger $logger;

    public function setLogger(AbstractLogger $logger): void {
        $this->logger = $logger;
    }
}
