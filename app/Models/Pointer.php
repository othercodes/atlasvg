<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pointer
 * @property int $id
 * @property string $name
 * @property string $meta
 * @property string $description
 * @property string $top
 * @property string $left
 * @property Space $space
 * @package AtlasVG\Models
 */
class Pointer extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'pointers';

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'top' => 'float',
        'left' => 'float',
    ];

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'name',
        'meta',
        'description',
        'top',
        'left',
    ];

    /**
     * The attributes that should be visible in arrays.
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'meta',
        'description',
        'top',
        'left',
        'room',
        'category',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'room'
    ];

    /**
     * The relations to eager load on every query.
     * @var array
     */
    protected $with = [
        'category'
    ];

    /**
     * Get the space that contains the pointer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo('AtlasVG\Models\Space');
    }

    /**
     * Get the space category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('AtlasVG\Models\Category');
    }

    /**
     * Get the assigned space data as room property
     * @return float
     */
    public function getRoomAttribute()
    {
        return $this->space->data;
    }
}
