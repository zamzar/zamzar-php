<?php declare(strict_types=1);

namespace Zamzar;

use PHPUnit\Framework\TestCase;

final class ImportsTest extends TestCase
{

    use TestConfig;

    public function testImportsAreListable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $imports = $zamzar->imports->all(['limit' => 1]);
        $this->assertEquals(count($imports->data), 1);
    }

    public function testImportsContainsPagingElements(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $imports = $zamzar->imports->all(['limit' => 1]);
        $paging = $imports->paging;
        $this->assertGreaterThan(0, $paging->limit);
        $this->assertGreaterThan(0, $paging->first);
        $this->assertGreaterThan(0, $paging->last);
        $this->assertGreaterThan(0, $paging->total_count);
    }

    public function testImportIsRetrievable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);

        // get any import
        $imports = $zamzar->imports->all(['limit' => 1]);
        $importid = $imports->data[0]->getId();
        //retrieve the import via the 'get' method
        $import = $zamzar->imports->get($importid);
        $this->assertGreaterThan(0, $import->getId());
    }

    public function testImportCanBeStarted(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $import = $zamzar->imports->start([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $this->assertGreaterThan(0, $import->getId());
    }

    public function testImportCanBeRefreshed(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $import = $zamzar->imports->start([
            'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
        ]);
        $statusBefore = $import->getStatus();
        sleep(10);
        $import->refresh();
        $statusAfter = $import->getStatus();
        $this->assertNotEquals($statusBefore, $statusAfter);
    }

}
