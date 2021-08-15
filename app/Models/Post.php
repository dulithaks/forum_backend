<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const STATUS_APPROVE = 1;
    const STATUS_PENDING = 0;

    protected $fillable = [
        'body',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
      'human_date',
      'latest_comments',
    ];

    public function scopeApprove($query)
    {
        return $query->where('status', self::STATUS_APPROVE);
    }

    public function user() {
        return $this->belongsTo(User::class)->select(['id', 'first_name', 'last_name', 'role']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getHumanDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getLatestCommentsAttribute()
    {
        return $this->comments()->with('user')->latest()->paginate(3);
    }
}
