<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'relationship',
        'province',
        'city',
        'address',
        'gender',
        'postal_code',
    ];

    protected $casts = [
        'gender' => 'string',
        'postal_code' => 'string',
    ];

    // accessor برای نام کامل (اختیاری)
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
