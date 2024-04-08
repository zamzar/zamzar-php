<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Zamzar\Job;

final class JobsTest extends TestCase
{
    use WithClient;

    private $sourceFilePath = __DIR__ . "/files/source/";
    private $targetFilePath = __DIR__ . "/files/target/";
    private $validLocalSourceFile = __DIR__ . '/files/source/test.pdf';
    private $validTargetFormat = 'doc';

    public function testJobsAreListable(): void
    {
        $jobs = $this->client()->jobs->all(['limit' => 1]);
        $this->assertEquals(count($jobs->data), 1);
    }

    public function testJobIsRetrievable(): void
    {
        $job = $this->client()->jobs->create([
            'source_file' => $this->validLocalSourceFile,
            'target_format' => $this->validTargetFormat
        ]);

        $job = $this->client()->jobs->get($job->id);
        $this->assertGreaterThan(0, $job->id);
    }

    public function testJobCanBeSubmittedForLocalFile(): void
    {
        $job = $this->client()->jobs->create([
            'source_file' => $this->validLocalSourceFile,
            'target_format' => $this->validTargetFormat,
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);
    }

    public function testJobCanBeSubmittedForUrlFile(): void
    {
        $job = $this->client()->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'jpg',
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);
    }

    public function testJobCanBeSubmittedForZamzarFile(): void
    {
        $file = $this->client()->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);

        $job = $this->client()->jobs->create([
            'source_file' => $file->id,
            'target_format' => 'png',
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);
    }

    public function testJobCanBeSubmittedWithOptions(): void
    {
        $history = [];
        $stack = HandlerStack::create();
        $stack->push(Middleware::history($history));
        $transport = new Client(['handler' => $stack]);

        $client = $this->client(['transport' => $transport]);
        $job = $client->jobs->create([
            'source_file' => $this->validLocalSourceFile,
            'target_format' => $this->validTargetFormat,
            'options' => [
                'quality' => 50,
                'ocr' => true,
            ]
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);

        // Ensure that the request body contains the options
        $actualBody = (string)$history[0]['request']->getBody();
        $this->assertStringContainsString('options', $actualBody);
        $this->assertStringContainsString('"quality":50', $actualBody);
        $this->assertStringContainsString('"ocr":true', $actualBody);
    }

    public function testCanListOnlySuccessfulJobs()
    {
        $jobs = $this->client()->jobs->all(['status' => Job::STATUS_SUCCESSFUL]);

        $this->assertEmpty(array_filter($jobs->data, function ($job) {
            return $job->status !== Job::STATUS_SUCCESSFUL;
        }));
    }

    public function testCanPageOnlySuccessfulJobs()
    {
        $jobs = $this->client()->jobs->all(['status' => Job::STATUS_SUCCESSFUL, 'limit' => 1]);

        $this->assertCount(1, $jobs);
        $this->assertTrue($jobs[0]->isStatusSuccessful());

        $jobs = $jobs->nextPage();

        $this->assertCount(1, $jobs);
        $this->assertTrue($jobs[0]->isStatusSuccessful());
    }

    public function testFilesCanBeDownloadedAndDeleted()
    {
        $job = $this->client()->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ])->waitForCompletion();

        $job->downloadTargetFiles($this->targetFilePath);

        $this->assertTrue(file_exists($this->targetFilePath . $job->target_files[0]->name));

        $job = $job->deleteTargetFiles();

        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $job->downloadTargetFiles($this->targetFilePath);
    }

    public function testJobCanBeCancelledAndStatusRefreshed()
    {
        $job = $this->client()->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ]);
        $job = $job->cancel();
        $this->assertEquals('cancelled', $job->status);
    }
}
