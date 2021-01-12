<?php

namespace Linkeys\UrlSigner\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Linkeys\UrlSigner\Contracts\Models\Group as GroupModelContract;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\NormaliserManagerContract;

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

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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