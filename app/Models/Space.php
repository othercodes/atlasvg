<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Spaces
 * @property int $id
 * @property string $type
 * @property float $data
 * @property float $x
 * @property float $y
 * @property float $width
 * @property float $height
 * @property float $radius
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
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'data' => 'float',
        'x' => 'float',
        'y' => 'float',
        'width' => 'float',
        'height' => 'float',
        'radius' => 'float',
    ];

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'type',
        'data',
        'x',
        'y',
        'width',
        'height',
        'radius',
    ];

    /**
     * The attributes that should be visible in serialization.
     * @var array
     */
    protected $visible = [
        'id',
        'type',
        'data',
        'x',
        'y',
        'width',
        'height',
        'radius',
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

    /**
     * Calculate the area
     * @return float
     */
    public function calculateArea(): float
    {
        switch ($this->type) {
            case'svg':
            case'rect':
                return round($this->width * $this->height, 2);
            case'circle':
                return round(3.14159 * pow($this->radius, 2), 2);
            default:
                return 0.0;
        }
    }

    /**
     * Calculate the center of the space
     * @return array
     */
    public function calculateCenter(): array
    {
        switch ($this->type) {
            case 'svg':
            case 'rect':
                return [
                    'x' => (float)($this->x + ($this->width / 2)),
                    'y' => (float)($this->y + ($this->height / 2)),
                ];
            case 'circle':
                return [
                    'x' => (float)$this->x,
                    'y' => (float)$this->y,
                ];
            case 'ellipse':
                return [
                    'x' => (float)$this->x,
                    'y' => (float)$this->y,
                ];
            default:
                return [];
        }
    }
}
