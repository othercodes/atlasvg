<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Building
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $surroundings
 * @property Collection|Level[] $levels
 * @package AtlasVG\Models
 */
class Building extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'buildings';

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'surroundings'
    ];

    /**
     * Get the levels for the building
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levels()
    {
        return $this->hasMany('AtlasVG\Models\Level');
    }
}
