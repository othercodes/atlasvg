<?php

namespace AtlasVG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthData
 * @property string $accessToken
 * @property string $refreshToken
 * @property int $tokenExpires
 * @property string $userName
 * @property string $userEmail
 * @property int $building_id
 * @package AtlasVG\Models
 */
class AuthData extends Model {
    /**
     * Table name
     * @var string
     */
    protected $table = 'authdata';

    /**
     * Mass assignable
     * @var array
     */
    protected $fillable = [
        'accessToken',
        'refreshToken',
        'tokenExpires',
        'userName',
        'userEmail',
        'building_id'
    ];

    /**
     * Get building that belongs to this authentication scope
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function building()
    {
        return $this->hasOne('AtlasVG\Models\Building');
    }
}