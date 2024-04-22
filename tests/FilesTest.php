<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class FilesTest extends TestCase
{
    use WithClient;

    private const MOCK_LARGE_FILE_ID = 0;

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

    public function testLargeFileCanBeUploaded(): void
    {
        // Create a file that's larger than the default PHP memory limit (128MB)
        $path = $this->createTempFile(256 * 1024); // 256MB
        $file = $this->client()->files->create([
            'name' => $path
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

    public function testLargeFileCanBeDownloaded(): void
    {
        $file = $this->client()->files->get(self::MOCK_LARGE_FILE_ID);
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

    private function createTempFile($sizeInKb) {
        // Generate a temporary file name
        $tempFile = tempnam(sys_get_temp_dir(), 'temp_');

        // Open the file for writing
        $handle = fopen($tempFile, 'w');

        // Write to the file to make it the desired size (in KB)
        // Each '*' character is one byte, and 1024 bytes are 1KB
        $bytes = $sizeInKb * 1024;

        // Write in chunks to avoid excessive memory usage
        $chunkSize = 1024; // 1KB chunks
        $chunks = intval($bytes / $chunkSize);
        $remainder = $bytes % $chunkSize;

        for ($i = 0; $i < $chunks; $i++) {
            fwrite($handle, str_repeat('*', $chunkSize));
        }

        if ($remainder > 0) {
            fwrite($handle, str_repeat('*', $remainder));
        }

        // Close the file handle
        fclose($handle);

        // Return the path to the temporary file
        return $tempFile;
    }
}
