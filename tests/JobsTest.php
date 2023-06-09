<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
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
        $jobs = $this->client->jobs->all(['limit' => 1]);
        $this->assertEquals(count($jobs->data), 1);
    }

    public function testJobIsRetrievable(): void
    {
        $job = $this->client->jobs->create([
            'source_file' => $this->validLocalSourceFile,
            'target_format' => $this->validTargetFormat
        ]);

        $job = $this->client->jobs->get($job->id);
        $this->assertGreaterThan(0, $job->id);
    }

    public function testJobCanBeSubmittedForLocalFile(): void
    {
        $job = $this->client->jobs->create([
            'source_file' => $this->validLocalSourceFile,
            'target_format' => $this->validTargetFormat,
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);
    }

    public function testJobCanBeSubmittedForUrlFile(): void
    {
        $job = $this->client->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'jpg',
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);
    }

    public function testJobCanBeSubmittedForZamzarFile(): void
    {
        $file = $this->client->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);

        $job = $this->client->jobs->create([
            'source_file' => $file->id,
            'target_format' => 'png',
        ])->waitForCompletion();

        $this->assertEquals($job->status, Job::STATUS_SUCCESSFUL);
    }

    public function testCanListOnlysuccessfulJobs()
    {
        $job = $this->client->jobs->create([
            'source_file' => 'https://www.zamzar.com/non-existant.png',
            'target_format' => 'jpg',
        ])->waitForCompletion();

        $this->assertEquals(Job::STATUS_FAILED, $job->status);

        $jobs = $this->client->jobs->all(['status' => Job::STATUS_SUCCESSFUL]);

        $this->assertEmpty(array_filter($jobs->data, fn ($job) => $job->status !== Job::STATUS_SUCCESSFUL));
    }

    public function testFilesCanBeDownloadedAndDeleted()
    {
        $job = $this->client->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ])->waitForCompletion();

        $job->downloadTargetFiles($this->targetFilePath);

        $this->assertEquals(file_exists($this->targetFilePath . 'zamzar-logo.pdf'), true);

        $job = $job->deleteTargetFiles($this->targetFilePath);

        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $job = $job->downloadTargetFiles($this->targetFilePath);
    }

    public function testJobCanBeCancelledandStatusRefreshed()
    {
        $job = $this->client->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ]);
        $job = $job->cancel();
        $this->assertEquals('cancelled', $job->status);
    }
}
