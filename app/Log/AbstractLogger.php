<?php


namespace App\Log;


abstract class AbstractLogger {

    public const TYPE_FILE = 'f';

    public const TYPE_CONSOLE = 'c';

    public static function get(?string $type = null): self {
        switch ($type) {
            case self::TYPE_FILE:
                $result = new FileLogger();
                break;
            case self::TYPE_CONSOLE:
            default:
                $result = new ConsoleLogger();
                break;
        }

        return $result;
    }

    abstract public function begin(): void;

    abstract public function info(string $message): void;

    abstract public function end(): void;
}
