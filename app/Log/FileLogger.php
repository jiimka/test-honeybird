<?php


namespace App\Log;


use RuntimeException;

final class FileLogger extends AbstractLogger {

    /** @var bool|resource */
    private $logFile;

    public function begin(): void {
        $this->logFile = fopen("output.log", 'wb');
        if (!is_resource($this->logFile)) {
            throw new RuntimeException('');
        }
    }

    public function info(string $message): void {
        fwrite($this->logFile, $message . "\n");
    }

    public function end(): void {
        echo fclose($this->logFile)
            ? "Output was saved to the `output.log` file \n"
            : "There occurred an error saving output to file` \n";
    }
}
