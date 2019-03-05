<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pointer
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
     * Get the space that contains the pointer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space()
    {
        return $this->belongsTo('AtlasVG\Models\Space');
    }
}
