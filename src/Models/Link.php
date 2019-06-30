<?php

namespace Linkeys\UrlSigner\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Linkeys\UrlSigner\Contracts\Models\Link as LinkModelContract;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\NormaliserManager;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\NormaliserManagerContract;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\ExpiryNormaliser;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromDateTime;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromInteger;
use Linkeys\UrlSigner\Support\ExpiryNormaliser\Normalisers\FromString;
use Linkeys\UrlSigner\Support\UrlManipulator\UrlManipulator;
use Linkeys\UrlSigner\Support\Uuid\UuidCreator;
use Ramsey\Uuid\Uuid;

class Link extends Model implements LinkModelContract
{

    public $fillable = [
        'url',
        'data',
        'expiry',
        'click_limit',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dates = [
        'expiry'
    ];

    /**
     * @var ExpiryNormaliser
     */
    protected $normaliser;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('links.tables.links');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = $model->uuid ?: resolve(UuidCreator::class)->create();
        });
    }

    public function setExpiryAttribute($expiry)
    {
        $normaliser = resolve(NormaliserManagerContract::class);
        $this->attributes['expiry'] = $normaliser->normalise($expiry);
    }

    public function getFullUrl()
    {
        $urlParser = resolve(UrlManipulator::class);
        $urlParser->setUrl($this->url);
        $urlParser->appendQuery([config('links.query_key') => $this->uuid]);
        return $urlParser->getUrl();
    }

    public function clickLimitReached()
    {
        if($this->click_limit === null) {
            return false;
        }
        return $this->click_limit <= $this->clicks;
    }

    public function expired()
    {
        if($this->expiry === null) {
            return false;
        }
        return !(new Carbon($this->expiry))->isFuture();
    }

}