<?php


namespace App\Models;


use App\Log\AbstractLogger;

final class FlowersSet {

    /** @var \App\Models\Flower[]|array */
    private array $flowers = [];

    public function __construct(int $size, AbstractLogger $logger) {
        for ($i = 1; $i <= $size; $i++) {
            $flower = new Flower($i);
            $flower->setLogger($logger);
            $this->flowers[] = $flower;
        }
    }

    /**
     * @return \App\Models\Flower[]|array
     */
    public function getFlowers(): array {
        return $this->flowers;
    }
}
