<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
   use HasFactory, SoftDeletes;

   protected $fillable = [
      'url',
      'short_url',
      'user_id',
   ];

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function analytics()
   {
      return $this->hasMany(UrlAnalytic::class);
   }

   public function referers()
   {
      return $this->hasManyThrough(UrlAnalytic::class, Url::class, 'id', 'url_id', 'id', 'id')
         ->selectRaw('referer, count(*) as aggregate')
         ->groupBy('referer');
   }
}
