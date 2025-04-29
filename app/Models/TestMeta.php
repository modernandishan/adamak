<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestMeta extends Model
{
    protected $fillable = [
        'test_id',
        'purpose',
        'target_age_group',
        'test_type',
        'approximate_duration',
        'required_tools',
        'analysis_method',
        'reliability_coefficient',
        'validity',
        'language_requirement',
        'iq_estimation_possibility',
        'main_applications',
        'strengths',
        'limitations',
        'advanced_versions',
        'advantages_of_execution',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
