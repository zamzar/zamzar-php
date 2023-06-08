<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class FormatsTest extends TestCase
{
    use WithClient;

    public function testFormatsAreListable(): void
    {
        $formats = $this->client->formats->all(['limit' => 1]);
        $this->assertEquals(count($formats->data), 1);
    }

    public function testFormatsContainsPagingElements(): void
    {
        $formats = $this->client->formats->all(['limit' => 1]);
        $paging = $formats->paging;
        $this->assertGreaterThan(0, $paging->limit);
        $this->assertGreaterThan(0, $paging->first);
        $this->assertGreaterThan(0, $paging->last);
        $this->assertGreaterThan(0, $paging->total_count);
    }

    public function testFormatIsRetrievable(): void
    {
        $format = $this->client->formats->get('png');
        $this->assertGreaterThan(0, count($format->targets));
    }
}
