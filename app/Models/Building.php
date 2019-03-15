<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLIterator;

/**
 * Class Building
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \SimpleXMLElement|string $svg
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
        'description',
        'map',
        'levels',
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
        'levels'
    ];

    /**
     * Get the levels for the building
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levels()
    {
        return $this->hasMany('AtlasVG\Models\Level');
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

        $this->attributes['svg'] = $svg->saveXML();
    }

    /**
     * Get surroundings map path
     * @return string
     */
    public function getMapAttribute($svg)
    {
        $path = resource_path("maps/b{$this->id}.surroundings.svg");
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
}
