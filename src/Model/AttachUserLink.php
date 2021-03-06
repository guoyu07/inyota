<?php

namespace InYota\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AttachUserLink extends Model
{
    protected $table = 'attach_user_links';

    protected $primaryKey = 'attach_link_id';

    public function scopeByUserId(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeByAttachId(Builder $query, $attach_id)
    {
        return $query->where('attach_id', $attach_id);
    }

    public function attach()
    {
        return $this->belongsTo(Attach::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
