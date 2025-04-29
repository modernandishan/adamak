<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
    ];

    /**
     * اعضای خانواده
     */
    public function members(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
