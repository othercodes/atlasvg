<?php

namespace Test\Models;

use AtlasVG\Models\Building;
use Illuminate\Database\Eloquent\Collection;
use Test\TestCase;

class BuildingTest extends TestCase
{

    public function testRelations()
    {
        $building = Building::find(1);

        $this->assertIsArray($building->toArray());
        $this->assertCount(7, $building->toArray());

        $this->assertInstanceOf(Collection::class, $building->levels);
        $this->assertCount(3, $building->levels);
    }
}
