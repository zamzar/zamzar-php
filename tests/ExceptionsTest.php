<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ExceptionsTest extends TestCase
{
    use WithClient;

    public function testAuthenticationException(): void
    {
        $this->expectException(\Zamzar\Exception\AuthenticationException::class);
        $zamzar = $this->client(['api_key' => 'invalid']);
        $zamzar->testConnection();
    }

    public function testInvalidArgumentException(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $this->client()->jobs->create([
            'sour1file' => 'invalid'
        ]);
    }

    public function testInvalidRequestException(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidRequestException::class);
        $job = $this->client()->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'unsupported'
        ]);
    }

    public function testInvalidResourceException(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $this->client()->files->get(1234);
    }

    public function testTimeOutException(): void
    {
        $this->expectException(\Zamzar\Exception\TimeOutException::class);
        $job = $this->client()->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ])->waitForCompletion(0);
    }

    // public function testPayloadException(): void
    // {
    //     // run the following separately, e.g. via tinkerwell, given the time taken to upload the 10gb file
    //     $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
    //     try {
    //         $job = $zamzar->jobs->create([
    //             'source_file' => 'tests/files/source/large-file-10gb.txt',
    //             'target_format' => 'pdf'
    //         ]);
    //     } catch (\Zamzar\Exception\PayloadException $e){
    //         echo $e->getMessage();
    //     }
    // }

    // public function testRateLimitException(): void
    // {
    //      // Run the following script multi threaded
    //      $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
    //      $x = 1;
    //      $max = 1000;
    //      do {
    //          try {
    //                $formats = $zamzar->formats->all(['limit' => 1]);
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
