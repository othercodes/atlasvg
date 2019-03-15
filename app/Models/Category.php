<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Level
 * @property int $id
 * @property string $name
 * @property string $color
 * @package AtlasVG\Models
 */
class Category extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'categories';

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * The attributes that should be visible in arrays.
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'color',
    ];

    /**
     * Get the pointers in the given category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pointers()
    {
        return $this->hasMany('AtlasVG\Models\Pointers');
    }
}
