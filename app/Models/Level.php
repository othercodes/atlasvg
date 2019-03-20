<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLIterator;

/**
 * Class Level
 * @property int $id
 * @property string $name
 * @property int $level
 * @property string $description
 * @property string $sign
 * @property SimpleXMLIterator $svg
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
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'description',
        'svg',
        'map',
    ];

    /**
     * The attributes that should be visible in arrays.
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'level',
        'description',
        'map',
        'pointers',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'map'
    ];

    /**
     * The relations to eager load on every query.
     * @var array
     */
    protected $with = [
        'pointers'
    ];

    /**
     * Deviation for x
     * @var float
     */
    private $xDeviation = 1.0;

    /**
     * deviation for y
     * @var float
     */
    private $yDeviation = -1.5;

    /**
     * The attribute that define which element of the svg is an "habitable" space.
     * @var string
     */
    private $spaceAttribute = 'data-space';

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

    /**
     * Get the pointer for the current level
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function pointers()
    {
        return $this->hasManyThrough('AtlasVG\Models\Pointer', 'AtlasVG\Models\Space');
    }

    /**
     * @param string $svg
     * @return SimpleXMLIterator
     */
    public function getSvgAttribute($svg)
    {
        if (isset($svg)) {
            return new SimpleXMLIterator($svg, LIBXML_COMPACT);
        }

        return null;
    }

    /**
     * Load the svg as SimpleXMLIterator
     * @param \SimpleXMLElement|string $svg
     */
    public function setSvgAttribute($svg)
    {
        $data_is_url = false;
        if (is_string($svg)) {

            if (is_readable($svg)) {
                $data_is_url = true;
            }

            $svg = new SimpleXMLIterator($svg, LIBXML_COMPACT, $data_is_url);
        }

        if (!($svg instanceof \SimpleXMLElement)) {
            throw new \InvalidArgumentException('Invalid argument svg, must be instance of '
                . 'SimpleXMLIterator, a valid path to a svg file or a valid svg/xml string.');
        }

        $this->attributes['sign'] = md5($svg->saveXML());
        $this->attributes['svg'] = $svg->saveXML();
    }

    /**
     * @param string $spaceAttribute
     */
    public function setSpaceAttribute(string $spaceAttribute)
    {
        $this->spaceAttribute = $spaceAttribute;
    }

    /**
     * @return string
     */
    public function getSpaceAttribute(): string
    {
        return $this->spaceAttribute;
    }

    /**
     * Get surroundings map path
     * @return string
     */
    public function getMapAttribute()
    {
        $path = resource_path("maps/b{$this->building->id}.l{$this->id}.svg");
        if (isset($this->svg)) {
            $this->svg->saveXML($path);
        }

        return $this->attributes['map'] = $path;
    }

    /**
     * Proxy to handle map import
     * @param string $map
     */
    public function setMapAttribute(string $map)
    {
        $this->setSvgAttribute($map);
    }

    /**
     * Return the X deviation
     * @return float
     */
    public function getXDeviation(): float
    {
        return $this->xDeviation;
    }

    /**
     * Set the X deviation
     * @param float $xDeviation
     */
    public function setXDeviation(float $xDeviation): void
    {
        $this->xDeviation = (float)$xDeviation;
    }

    /**
     * Return the Y deviation
     * @return float
     */
    public function getYDeviation(): float
    {
        return $this->yDeviation;
    }

    /**
     * Set the Y deviation
     * @param float $yDeviation
     */
    public function setYDeviation(float $yDeviation): void
    {
        $this->yDeviation = (float)$yDeviation;
    }

    /**
     * Process the svg to discover the available spaces
     * @return Space[]|Collection
     */
    public function discover(): Collection
    {
        $viewBox = explode(' ', $this->svg['viewBox']);
        $this->buildSpaceModel([
            'type' => 'svg',
            'data' => (float)$this->id,
            'x' => (float)0.0,
            'y' => (float)0.0,
            'width' => (float)$viewBox[2],
            'height' => (float)$viewBox[3],
        ]);

        $this->parse($this->svg);

        return $this->spaces()->get();
    }

    /**
     * Parse the svg file to extract the spaces
     * @param \SimpleXMLElement $parent
     */
    protected function parse(\SimpleXMLElement $parent): void
    {
        /** @var SimpleXMLIterator $node */
        foreach ($parent as $node) {
            if (isset($node[$this->spaceAttribute])) {
                switch ($node->getName()) {
                    case 'rect':
                        if ($this->validate($node, ['x', 'y', 'width', 'height'])) {
                            $this->buildSpaceModel([
                                'type' => $node->getName(),
                                'data' => (float)$node[$this->spaceAttribute],
                                'x' => (float)$node['x'],
                                'y' => (float)$node['y'],
                                'width' => (float)$node['width'],
                                'height' => (float)$node['height'],
                            ]);

                        }
                        break;

                    case 'circle':
                        if ($this->validate($node, ['cx', 'cy', 'r'])) {
                            $this->buildSpaceModel([
                                'type' => $node->getName(),
                                'data' => (float)$node[$this->spaceAttribute],
                                'x' => (float)$node['cx'],
                                'y' => (float)$node['cy'],
                                'radius' => (float)$node['r']
                            ]);

                        }
                        break;
                    default:
                        continue;
                }
            }

            if (count($node->children()) > 0) {
                $this->parse($node->children());
            }
        }
    }

    /**
     * Dynamically search and build a space model
     * @param $attributes
     * @return Space
     */
    private function buildSpaceModel($attributes): Model
    {
        $is_new = false;
        $query = Space::where('level_id', '=', $this->id);
        foreach ($attributes as $field => $value) {
            if (is_scalar($value)) {
                $query->where($field, '=', $value);
            }
        }

        $space = $query->first();
        if (!isset($space)) {
            $space = new Space();
            $is_new = true;
        }

        $space->fill($attributes);

        if ($is_new) {
            $space->level()->associate($this)->save();
        }

        return $space;
    }

    /**
     * @param \SimpleXMLElement $node
     * @param array $requiredAttributes
     * @return bool
     */
    private function validate(\SimpleXMLElement $node, array $requiredAttributes): bool
    {
        $missing = array_filter($requiredAttributes, function ($attribute) use ($node) {
            return !isset($node[$attribute]);
        });

        return (count($missing) === 0);
    }

    /**
     * @param Space $space
     * @return array
     */
    public function calculateRelativeSpaceCenter(Space $space): array
    {
        $viewBox = explode(' ', $this->svg['viewBox']);
        $center = $space->calculateCenter();

        return [
            'x' => (int)(($center['x'] * 100.00) / $viewBox[2]) - $this->xDeviation,
            'y' => (int)(($center['y'] * ($viewBox[3] * 100 / $viewBox[2])) / $viewBox[3]) - $this->yDeviation,
        ];
    }

    /**
     * Return the width and height of the level
     * @return array
     */
    public function calculateRelativeWidthAndHeight(): array
    {
        $viewBox = explode(' ', $this->svg['viewBox']);

        return [
            'left' => 100 / 2,
            'top' => $viewBox[3] * 100 / $viewBox[2] / 2,
            'width' => 100,
            'height' => $viewBox[3] * 100 / $viewBox[2],
        ];
    }
}
