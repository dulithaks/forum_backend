<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const STATUS_APPROVE = 1;
    const STATUS_PENDING = 0;

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeApprove($query)
    {
        return $query->where('status', self::STATUS_APPROVE);
    }

    public function user() {
        return $this->belongsTo(User::class)->select(['id', 'first_name', 'last_name', 'role']);
    }
}
