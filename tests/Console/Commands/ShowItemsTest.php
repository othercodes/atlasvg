<?php

namespace Test\Console\Commands;

use Test\TestCase;

/**
 * Class ShowItemsTest
 * @package Test\Console\Commands
 */
class ShowItemsTest extends TestCase
{
    public function testShowCategories()
    {
        $this->assertEquals(0, $this->artisan('atlasvg:show', ['item' => 'categories']));
    }

    public function testShowBuildings()
    {
        $this->assertEquals(0, $this->artisan('atlasvg:show', ['item' => 'buildings']));
    }

    public function testShowLevels()
    {
        $this->assertEquals(0, $this->artisan('atlasvg:show', ['item' => 'levels']));
    }

    public function testShowSpaces()
    {
        $this->assertEquals(0, $this->artisan('atlasvg:show', ['item' => 'spaces']));
    }

    public function testShowPointers()
    {
        $this->assertEquals(0, $this->artisan('atlasvg:show', ['item' => 'pointers']));
    }

    public function testFailShowUnknownItem()
    {
        $this->assertNotEquals(0, $this->artisan('atlasvg:show', ['item' => 'unknown']));
    }
}