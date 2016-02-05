<?php
/**
 * User.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:33.
 */

namespace Cerberus\Models;

use Hashids;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class User extends \Einherjars\Carbuncle\Users\Eloquent\User implements UserContract
{
    /**
     * Set the Carbuncle User Model Hasher to be the same as the configured Carbuncle Hasher
     */
    public static function boot()
    {
        parent::boot();
        static::setHasher(app()->make('carbuncle.hasher'));
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->getPersistCode();
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->persist_code = $value;

        $this->save();
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return "persist_code";
    }

    /**
     * Use a mutator to derive the appropriate hash for this user
     *
     * @return mixed
     */
    public function getHashAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}
