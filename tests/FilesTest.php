<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class FilesTest extends TestCase
{
    use TestConfig;

    public function testFilesAreListable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $files = $zamzar->files->all(['limit' => 1]);
        $this->assertEquals(count($files->data), 1);
    }

    public function testFilesContainsPagingElements(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $files = $zamzar->files->all(['limit' => 1]);
        $paging = $files->paging;
        $this->assertGreaterThan(0, $paging->limit);
        $this->assertGreaterThan(0, $paging->first);
        $this->assertGreaterThan(0, $paging->last);
        $this->assertGreaterThan(0, $paging->total_count);
    }

    public function testFileIsRetrievable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);

        //get any file
        $files = $zamzar->files->all(['limit' => 1]);
        $fileid = $files->data[0]->getId();
        $file = $zamzar->files->get($fileid);

        //retrieve the file via the 'get' method
        $this->assertGreaterThan(0, $file->getSize());
    }

    public function testFileCanBeUploaded(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $file = $zamzar->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $this->assertGreaterThan(0, $file->getId());
    }

    public function testFileCanBeDownloaded(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $file = $zamzar->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $file->download(__DIR__ . '/files/target/');
        $this->assertEquals(file_exists(__DIR__ . '/files/target/' . $file->getName()), true);
    }

    public function testFileCanBeDownloadedWithCustomFilename(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $file = $zamzar->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $file->download(__DIR__ . '/files/target/output.pdf');
        $this->assertEquals(file_exists(__DIR__ . '/files/target/output.pdf'), true);
    }

    public function testFileCanBeDeleted(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $files = $zamzar->files->all(['limit' => 1]);
        $fileid = $files->data[0]->getId();
        $file = $zamzar->files->get($fileid);
        $file->delete();
        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $file->download(__DIR__ . '/files/target/');
    }

    public function testFileCanBeConverted(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $files = $zamzar->files->all(['limit' => 1]);
        $fileid = $files->data[0]->getId();
        $file = $zamzar->files->get($fileid);

        $format = $zamzar->formats->get(pathinfo($file->getName(), PATHINFO_EXTENSION));
        $targetFormat = $format->getTargets()[0]->getName();

        $job = $file->convert([
            'target_format' => $targetFormat
        ])->waitForCompletion();

        $this->assertGreaterThan(0, $job->getTargetFiles()[0]->getSize());
    }
}
