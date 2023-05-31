<?php

declare(strict_types=1);

namespace Zamzar;

use PHPUnit\Framework\TestCase;

final class FormatsTest extends TestCase
{
    use TestConfig;

    public function testFormatsAreListable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $formats = $zamzar->formats->all(['limit' => 1]);
        $this->assertEquals(count($formats->data), 1);
    }

    public function testFormatsContainsPagingElements(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);
        $formats = $zamzar->formats->all(['limit' => 1]);
        $paging = $formats->paging;
        $this->assertGreaterThan(0, $paging->limit);
        $this->assertGreaterThan(0, $paging->first);
        $this->assertGreaterThan(0, $paging->last);
        $this->assertGreaterThan(0, $paging->total_count);
    }

    public function testFormatIsRetrievable(): void
    {
        $zamzar = new \Zamzar\ZamzarClient($this->apiKey);

        // get any format
        $formats = $zamzar->formats->all(['limit' => 1]);
        $format = $formats->data[0]->getName();
        //retrieve the format via the 'get' method
        $format = $zamzar->formats->get($format);
        $this->assertGreaterThan(0, count($format->getTargets()));
    }
}
