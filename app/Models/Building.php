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
        'surroundings',
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
     * @param string $surroundings
     * @return SimpleXMLIterator
     */
    public function getSurroundingsAttribute($surroundings)
    {
        return new SimpleXMLIterator($surroundings, LIBXML_COMPACT);
    }

    /**
     * Load the svg as SimpleXMLIterator
     * @param \SimpleXMLElement|string $surroundings
     */
    public function setSurroundingsAttribute($surroundings)
    {
        $data_is_url = false;
        if (is_string($surroundings)) {

            if (is_readable($surroundings)) {
                $data_is_url = true;
            }

            $surroundings = new SimpleXMLIterator($surroundings, LIBXML_COMPACT, $data_is_url);
        }

        if (!($surroundings instanceof \SimpleXMLElement)) {
            throw new \InvalidArgumentException('Invalid argument surroundings, must be instance of '
                . 'SimpleXMLIterator, a valid path to a svg file or a valid svg/xml string.');
        }

        $this->attributes['surroundings'] = $surroundings->saveXML();
    }
}
