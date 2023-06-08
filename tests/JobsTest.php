<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class JobsTest extends TestCase
{
    use TestConfig;

    private $sourceFilePath = "tests/files/source/";
    private $targetFilePath = "tests/files/target/";
    private $validLocalSourceFile = 'tests/files/source/test.pdf';
    private $validTargetFormat = 'doc';

    public function testJobsAreListable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $jobs = $zamzar->jobs->all(['limit' => 1]);
        $this->assertEquals(count($jobs->data), 1);
    }

    public function testJobsContainsPagingElements(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $jobs = $zamzar->jobs->all(['limit' => 1]);
        $paging = $jobs->paging;
        $this->assertGreaterThan(0, $paging->limit);
        $this->assertGreaterThan(0, $paging->first);
        $this->assertGreaterThan(0, $paging->last);
        $this->assertGreaterThan(0, $paging->total_count);
    }

    public function testJobIsRetrievable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);

        //get a job
        $jobs = $zamzar->jobs->all(['limit' => 1]);
        $jobid = $jobs->data[0]->getId();

        //retrieve the job via the 'get' method
        $job = $zamzar->jobs->get($jobid);
        $this->assertGreaterThan(0, $job->getId());
    }

    public function testJobCanBeSubmittedForLocalFile(): void
    {
        // remote files are tested as part of subsequent tests, so no need to test individually
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $job = $zamzar->jobs->create([
            'source_file' => $this->validLocalSourceFile,
            'target_format' => $this->validTargetFormat
        ]);
        $this->assertEquals($job->getStatus(), 'initialising');

        $job->waitForCompletion();
    }

    public function testJobCanBeSubmittedForZamzarFile(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);

        //get a file which should be a pdf based on the previous function
        $files = $zamzar->files->all(['limit' => 1]);
        $fileid = $files->data[0]->getId();

        //submit a job using the file id
        $job = $zamzar->jobs->create([
            'source_file' => $fileid,
            'target_format' => 'pdf'
        ]);
        $this->assertEquals($job->getStatus(), 'initialising');
    }

    public function testInvalidParameterException(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $job = $zamzar->jobs->create([
            'so1urce_file' => 'anything',
            'tar1get_format' => 'anything'
        ]);
    }

    public function testFilesCanBeDownloadedAndDeleted()
    {

        //submit a job and wait for completion
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $job = $zamzar->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ])->waitForCompletion();

        //download the target file
        $job->downloadTargetFiles($this->targetFilePath);

        //confirm the file has been downloaded
        $this->assertEquals(file_exists($this->targetFilePath . 'zamzar-logo.pdf'), true);

        //download and delete all files which repeats the download via a different function
        $job = $job->downloadAndDeleteAllFiles($this->targetFilePath);

        //assert that the files cannot be downloaded again
        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $job = $job->downloadAndDeleteAllFiles($this->targetFilePath);
    }

    public function testJobCanBeCancelledandStatusRefreshed()
    {
        //submit a job and wait for completion
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $job = $zamzar->jobs->create([
            'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
            'target_format' => 'pdf'
        ]);
        $job = $job->cancel();
        $this->assertEquals($job->getStatus(), 'cancelled');
    }
}
