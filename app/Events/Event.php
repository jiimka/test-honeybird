<?php


namespace App\Events;


use SplObjectStorage;
use SplObserver;
use SplSubject;

abstract class Event implements SplSubject {

    /** @var \SplObjectStorage */
    private SplObjectStorage $observers;

    public function __construct() {
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer) {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer) {
        $this->observers->detach($observer);
    }

    public function notify() {
        /** @var SplObserver $observer */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getObservers(): SplObjectStorage {
        return $this->observers;
    }
}
