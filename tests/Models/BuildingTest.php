<?php

namespace Test\Models;

use AtlasVG\Models\Building;
use Illuminate\Database\Eloquent\Collection;
use Test\TestCase;

class BuildingTest extends TestCase
{

    public function testRelations()
    {
        Building::all()->each(function (Building $building) {
            $this->assertIsArray($building->toArray());
            $this->assertCount(6, $building->toArray());

            $this->assertInstanceOf(Collection::class, $building->levels);
            $this->assertCount(3, $building->levels);
        });
    }
}
