<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'thumbnail',
    ];

    public function questions()
    {
        return $this->hasMany(TestQuestion::class);
    }

    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function meta()
    {
        return $this->hasOne(TestMeta::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($test) {
            if (empty($test->slug)) {
                $test->slug = Str::slug($test->title);
            }
        });
    }
}
