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
    ];
}