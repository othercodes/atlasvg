<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Building
 * @package App\Models
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
        'id',
        'name',
        'description',
    ];
}
