<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

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
            'target_format' => $this->validTargetFormat
        ]);
        $this->assertEquals($job->status, 'initialising');
    }

    public function testJobCanBeSubmittedForZamzarFile(): void
    {
        $file = $this->client->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);

        $job = $this->client->jobs->create([
            'source_file' => $file->id,
            'target_format' => 'png'
        ]);
        $this->assertEquals($job->status, 'initialising');
    }

    public function testInvalidParameterException(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $job = $this->client->jobs->create([
            'so1urce_file' => 'anything',
            'tar1get_format' => 'anything'
        ]);
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
