<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileUpload extends Model
{
    protected $fillable = [
        'table_name',
        'name',
        'gender'
    ];
}
