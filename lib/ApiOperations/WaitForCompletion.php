<?php

namespace Zamzar\ApiOperations;

use Zamzar\Exception\TimeOutException;

trait WaitForCompletion
{
    public function waitForCompletion($timeout = 60)
    {
        $interval = 1;
        $start = time();

        do {
            sleep($interval);

            $duration = time() - $start;
            if ($duration > 60) {
                $interval = 30;
            } elseif ($duration > 40) {
                $interval = 20;
            } elseif ($duration > 20) {
                $interval = 10;
            } elseif ($duration > 10) {
                $interval = 5;
            }

            if ($duration > $timeout) {
                throw new TimeOutException("Timed out after waiting $duration seconds for " . static::class . "(ID: $this->id) to complete");
            }

            $this->refresh();
        } while (!$this->hasCompleted());

        return $this;
    }
}
