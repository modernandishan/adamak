<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'full_name',
        'relation',
        'gender',
        'birth_date',
    ];

    public const RELATIONS = [
        1 => 'پدر',
        2 => 'مادر',
        3 => 'پسر',
        4 => 'دختر',
    ];

    public const GENDERS = [
        1 => 'مرد',
        2 => 'زن',
    ];

    /**
     * خانواده‌ی مربوطه
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * متن رابطه (نسبت) به صورت قابل خواندن
     */
    public function getRelationTitleAttribute(): string
    {
        return self::RELATIONS[$this->relation] ?? 'نامشخص';
    }

    /**
     * متن جنسیت
     */
    public function getGenderTitleAttribute(): string
    {
        return self::GENDERS[$this->gender] ?? 'نامشخص';
    }
}
