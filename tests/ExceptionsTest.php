<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ExceptionsTest extends TestCase
{
    use TestConfig;

    public function testAuthenticationException(): void
    {
        $this->expectException(\Zamzar\Exception\AuthenticationException::class);
        $zamzar = new \Zamzar\ZamzarClient('invalid');
        $zamzar->testConnection();
    }

    public function testInvalidArgumentException(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $zamzar->jobs->submit([
            'sour1file' => 'invalid'
        ]);
    }

    public function testInvalidRequestException(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidRequestException::class);
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $job = $zamzar->jobs->submit([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'mpg'
        ]);
    }

    public function testInvalidResourceException(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $zamzar->files->get(1234);
    }

    public function testTimeOutException(): void
    {
        $this->expectException(\Zamzar\Exception\TimeOutException::class);
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $job = $zamzar->jobs->submit([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ])->waitForCompletion(0);
    }

    // public function testPayloadException(): void
    // {
    //     // run the following separately, e.g. via tinkerwell, given the time taken to upload the 10gb file
    //     $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
    //     try {
    //         $job = $zamzar->jobs->submit([
    //             'source_file' => 'tests/files/source/large-file-10gb.txt',
    //             'target_format' => 'pdf'
    //         ]);
    //     } catch (\Zamzar\Exception\PayloadException $e){
    //         echo $e->getMessage();
    //     }
    // }

    // public function testRateLimitException(): void
    // {
    //      Ran the following script multi threaded and then used the SDK Sampler to catch ratelimit exceptions on the formats page.
    //      $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
    //      $x = 1;
    //      $max = 1000;
    //      do {
    //          try {
    //                  $formats = $zamzar->formats->all(['limit' => 1]);
    //              } catch (\Zamzar\Exception\RateLimitException $e) {
    //                  echo $e->getMessage();
    //              } catch (\Zamzar\Exception\ApiErrorException $e) {
    //                  echo $e->getMessage();
    //          }
    //          $x += 1;
    //      } while (
    //          $x < $max
    //      );
    // }
}
