<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'human_date'
    ];

    public function user() {
        return $this->belongsTo(User::class)->select(['id', 'first_name', 'last_name', 'role']);
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function getHumanDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
