<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Spaces
 * @property string $data
 * @property Level $level
 * @property Collection|Pointer[] $pointers
 * @package AtlasVG\Models
 */
class Space extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'spaces';

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'data',
    ];

    /**
     * Get the level that holds the space
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo('AtlasVG\Models\Level');
    }

    /**
     * Get the pointers in the given category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pointers()
    {
        return $this->hasMany('AtlasVG\Models\Pointer');
    }
}
