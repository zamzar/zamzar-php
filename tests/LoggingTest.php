<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zamzar\Util\LoggerInterface;
use Zamzar\Zamzar;
use Zamzar\ZamzarClient;

final class LoggingTest extends TestCase
{
    use WithClient;

    public function testDebugDefaultsToFalse(): void
    {
        $logger = $this->createLogger();

        Zamzar::setLogger($logger);

        $this->client()->account->get();

        $this->assertEmpty($logger->log);
    }

    public function testRequestsAreLogged(): void
    {
        $logger = $this->createLogger();

        Zamzar::setLogger($logger);

        $this->client(['debug' => true])->account->get();

        $this->assertCount(1, $logger->log);
    }

    public function testLoggerCanBeChanged(): void
    {
        $client = $this->client(['debug' => true]);

        $logger1 = $this->createLogger();
        $logger2 = $this->createLogger();

        Zamzar::setLogger($logger1);

        $client->account->get();

        Zamzar::setLogger($logger2);

        $client->account->get();

        $this->assertCount(1, $logger1->log);
        $this->assertCount(1, $logger2->log);
    }

    protected function createLogger()
    {
        return new class implements LoggerInterface {
            public $log = [];

            public function error($message, array $context = [])
            {
                $this->log[] = $message;
            }

            public function info($message, array $context = [])
            {
                $this->log[] = $message;
            }
        };
    }
}
