<?php

namespace GuidoCella\Multilingual\Models;

use GuidoCella\Multilingual\Translatable;
use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    use Translatable;

    public $translatable = ['name'];
    public $fillable = ['name'];
    public $timestamps = false;
}
