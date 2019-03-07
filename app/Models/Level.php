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
     * Deviation for x
     * @var float
     */
    private $xDeviation = 3.0;

    /**
     * deviation for y
     * @var float
     */
    private $yDeviation = 0.0;

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
     * @param string $svg
     * @return SimpleXMLIterator
     */
    public function getSvgAttribute($svg)
    {
        return new SimpleXMLIterator($svg, LIBXML_COMPACT);
    }

    /**
     * @param \SimpleXMLElement $svg
     */
    public function setSvgAttribute(\SimpleXMLElement $svg)
    {
        $this->attributes['sign'] = md5($svg->saveXML());
        $this->attributes['svg'] = $svg->saveXML();
    }

    /**
     * @param string $spaceAttribute
     * @return Level
     */
    public function setSpaceAttribute(string $spaceAttribute): Level
    {
        $this->spaceAttribute = $spaceAttribute;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpaceAttribute(): string
    {
        return $this->spaceAttribute;
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
        foreach ($this->svg as $node) {
            if (isset($node[$this->spaceAttribute])) {
                switch ($node->getName()) {
                    case 'rect':
                        if ($this->validate($node, ['x', 'y', 'width', 'height'])) {
                            $space = new Space([
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
                            $space = new Space([
                                'type' => $node->getName(),
                                'data' => (float)$node[$this->spaceAttribute],
                                'x' => (float)$node['cx'],
                                'y' => (float)$node['cy'],
                                'radius' => (float)$node['r']
                            ]);
                        }

                        break;
                    default:
                        continue 2;
                }

                $space->level()->associate($this);
                $space->save();

            }
        }

        return $this->spaces;
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
            'y' => (int)(($center['y'] * 66.667) / $viewBox[3]) - $this->yDeviation,
        ];
    }

}
