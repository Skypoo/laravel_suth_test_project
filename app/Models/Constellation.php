<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constellation extends Model
{
    protected $table = 'constellation';

    public $timestamps = true;

    protected $fillable = [
        'date',
        'constellation_name',
        'overall_level',
        'overall_content',
        'love_level',
        'love_content',
        'business_level',
        'business_content',
        'fortune_level',
        'fortune_content',
        'created_at',
        'updated_at',
    ];
}
