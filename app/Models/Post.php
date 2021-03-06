<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_APPROVE = 1;
    const STATUS_REJECT = 2;

    const FILTER_PENDING_POSTS = 'pending-posts';

    protected $fillable = [
        'user_id',
        'body',
        'status',
    ];

    protected $hidden = [
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

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproveAndPending($query)
    {
        return $query->whereIn('status', [self::STATUS_APPROVE, self::STATUS_PENDING]);
    }

    public function user()
    {
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
