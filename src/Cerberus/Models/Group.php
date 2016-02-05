<?php
/**
 * Group.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:33.
 */

namespace Cerberus\Models;

use Hashids;

class Group extends \Einherjars\Carbuncle\Groups\Eloquent\Group
{
    /**
     * Use a mutator to derive the appropriate hash for this group
     *
     * @return mixed
     */
    public function getHashAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}
