<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ImportsTest extends TestCase
{
    use WithClient;

    public function testImportsAreListable(): void
    {
        $imports = $this->client->imports->all(['limit' => 1]);
        $this->assertEquals(count($imports->data), 1);
    }

    public function testImportsContainsPagingElements(): void
    {
        $imports = $this->client->imports->all(['limit' => 1]);
        $paging = $imports->paging;
        $this->assertGreaterThan(0, $paging->limit);
        $this->assertGreaterThan(0, $paging->first);
        $this->assertGreaterThan(0, $paging->last);
        $this->assertGreaterThan(0, $paging->total_count);
    }

    public function testImportIsRetrievable(): void
    {
        $import = $this->client->imports->create([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $import = $this->client->imports->get($import->id);
        $this->assertGreaterThan(0, $import->id);
    }

    public function testImportCanBeStarted(): void
    {
        $import = $this->client->imports->create([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $this->assertGreaterThan(0, $import->id);
    }

    public function testImportCanBeRefreshed(): void
    {
        $import = $this->client->imports->create([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $statusBefore = $import->status;
        sleep(10);
        $import->refresh();
        $statusAfter = $import->status;
        $this->assertNotEquals($statusBefore, $statusAfter);
    }
}
