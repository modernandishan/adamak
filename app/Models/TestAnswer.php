<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_response_id',
        'test_question_id',
        'answer',
    ];

    public function response()
    {
        return $this->belongsTo(TestResponse::class, 'test_response_id');
    }

    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id');
    }
}
