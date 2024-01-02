<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrlAnalytic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'url_id',
        'ip_address',
        'device',
        'platform',
        'browser',
        'referer',
        'country',
    ];

    protected $casts = [
        'url_id' => 'integer',
        'ip_address' => 'string',
        'device' => 'string',
        'platform' => 'string',
        'browser' => 'string',
        'referer' => 'string',
        'country' => 'string',
    ];

    public function url()
    {
        return $this->belongsTo(Url::class);
    }
}
