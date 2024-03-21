<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class FilesTest extends TestCase
{
    use WithClient;

    public function testFilesAreListable(): void
    {
        $files = $this->client()->files->all(['limit' => 1]);
        $this->assertEquals(count($files->data), 1);
    }

    public function testFileIsRetrievable(): void
    {
        $files = $this->client()->files->all(['limit' => 1]);
        $fileid = $files->data[0]->getId();
        $file = $this->client()->files->get($fileid);

        $this->assertGreaterThan(0, $file->size);
    }

    public function testFileCanBeUploaded(): void
    {
        $file = $this->client()->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $this->assertGreaterThan(0, $file->id);
    }

    public function testFileCanBeDownloaded(): void
    {
        $file = $this->client()->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $file->download(__DIR__ . '/files/target/');
        $this->assertEquals(file_exists(__DIR__ . '/files/target/' . $file->name), true);
    }

    public function testFileCanBeDownloadedWithCustomFilename(): void
    {
        $file = $this->client()->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $file->download(__DIR__ . '/files/target/output.pdf');
        $this->assertEquals(file_exists(__DIR__ . '/files/target/output.pdf'), true);
    }

    public function testFileCanBeDeleted(): void
    {
        $file = $this->client()->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);
        $file->delete();
        $this->expectException(\Zamzar\Exception\InvalidResourceException::class);
        $file->download(__DIR__ . '/files/target/');
    }

    public function testFileCanBeConverted(): void
    {
        $file = $this->client()->files->create([
            'name' => __DIR__ . '/files/source/test.pdf'
        ]);

        $job = $file->convert([
            'target_format' => 'png'
        ])->waitForCompletion();

        $this->assertGreaterThan(0, $job->target_files[0]->size);
    }
}
