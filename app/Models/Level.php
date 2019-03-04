<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Level
 * @property string $name
 * @property int $level
 * @property string $description
 * @property string $sign
 * @property string $svg
 * @property Building $building
 * @property Collection|Space[] $spaces
 * @package AtlasVG\Models
 */
class Level extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'levels';

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'description',
        'svg',
    ];

    /**
     * Get the building that holds the level
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo('AtlasVG\Models\Building');
    }

    /**
     * Get the levels for the building
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spaces()
    {
        return $this->hasMany('AtlasVG\Models\Space');
    }
}
