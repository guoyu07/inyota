<?php

namespace InYota\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feed extends Model
{
    use SoftDeletes;

    public function attachs()
    {
        return $this->belongsToMany(Attach::class, 'feed_attach_links', 'feed_id', 'attach_id')
            ->withTimestamps();
    }

    public function diggs()
    {
        return $this->hasMany(FeedDigg::class);
    }
}
