<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ImportsTest extends TestCase
{
    use WithClient;

    public function testImportsAreListable(): void
    {
        $imports = $this->client()->imports->all(['limit' => 1]);
        $this->assertEquals(count($imports->data), 1);
    }

    public function testImportIsRetrievable(): void
    {
        $import = $this->client()->imports->create([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $import = $this->client()->imports->get($import->id);
        $this->assertGreaterThan(0, $import->id);
    }

    public function testImportCanBeStarted(): void
    {
        $import = $this->client()->imports->create([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $this->assertGreaterThan(0, $import->id);
    }

    public function testCanWaitUntilComplete(): void
    {
        $import = $this->client()->imports->create([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ])->waitForCompletion();
        $this->assertTrue($import->hasCompleted());
    }
}
