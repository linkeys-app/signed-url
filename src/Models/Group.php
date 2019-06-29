<?php

namespace Linkeys\LinkGenerator\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Linkeys\LinkGenerator\Contracts\Models\Group as GroupModelContract;
use Linkeys\LinkGenerator\Support\ExpiryNormaliser\NormaliserManagerContract;

class Group extends Model implements GroupModelContract
{

    public $fillable = [
        'expiry',
        'click_limit'
    ];

    protected $dates = [
        'expiry'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('links.tables.groups');
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }


    public function setExpiryAttribute($expiry)
    {
        $normaliser = resolve(NormaliserManagerContract::class);
        $normalisedExpiry = $normaliser->normalise($expiry);
        $this->attributes['expiry'] = $normalisedExpiry;
    }

    public function clickLimitReached()
    {
        if($this->click_limit === null) {
            return false;
        }
        return $this->click_limit <= $this->links->sum('clicks');
    }

    public function expired()
    {
        if($this->expiry === null) {
            return false;
        }
        return !(new Carbon($this->expiry))->isFuture();
    }

}