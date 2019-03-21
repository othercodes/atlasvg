<?php

namespace Test\Models;

use AtlasVG\Models\Building;
use AtlasVG\Models\Level;
use Illuminate\Database\Eloquent\Collection;
use Test\TestCase;

class LevelTest extends TestCase
{

    public function testRelations()
    {
        $level = Level::find(1);
        $this->assertIsArray($level->toArray());
        $this->assertCount(6, $level->toArray());

        $this->assertInstanceOf(Building::class, $level->building);
        $this->assertInstanceOf(Collection::class, $level->spaces);
        $this->assertCount(7, $level->spaces);

    }
}
