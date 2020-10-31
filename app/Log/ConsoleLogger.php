<?php


namespace App\Log;


final class ConsoleLogger extends AbstractLogger {

    public function info(string $message): void {
        echo $message . "\n";
    }

    public function begin(): void {
    }

    public function end(): void {
    }
}
