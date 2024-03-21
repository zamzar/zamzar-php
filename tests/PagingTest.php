<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zamzar\Collection;

final class PagingTest extends TestCase
{
    use WithClient;

    public function testCanChangePageSize()
    {
        $formats = $this->client()->formats->all(['limit' => 2]);

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(2, $formats);
    }

    public function testCanRequestNextPage()
    {
        $formats = $this->client()->formats->all(['limit' => 2]);

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(2, $formats);
        $format1 = $formats[0];
        $format2 = $formats[1];

        $formats = $formats->nextPage();

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(2, $formats);
        $this->assertNotEquals($format1->name, $formats[0]->name);
        $this->assertNotEquals($format2->name, $formats[1]->name);
    }

    public function testCanRequestPreviousPage()
    {
        $formats = $this->client()->formats->all(['limit' => 2]);

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(2, $formats);

        $formats = $formats->previousPage();

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(0, $formats);
    }

    public function testCanRequestNextPageAndChangeLimit()
    {
        $formats = $this->client()->formats->all(['limit' => 2]);

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(2, $formats);

        $formats = $formats->nextPage(['limit' => 10]);

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(10, $formats);
    }

    public function testCanRequestPreviousPageAndChangeLimit()
    {
        $formats = $this->client()->formats->all();
        $formats = $formats->nextPage();
        $formats = $formats->previousPage(['limit' => '5']);

        $this->assertInstanceOf(Collection::class, $formats);
        $this->assertCount(5, $formats);
    }
}
