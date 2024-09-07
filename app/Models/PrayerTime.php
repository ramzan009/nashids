<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;;

class PrayerTime extends Model
{
    use HasFactory;
    use CrudTrait;
    use SoftDeletes;

    protected $table = "prayer_times";

    protected $fillable = [
        'date',
        'fajr',
        'zuhr',
        'asr',
        'maghreb',
        'isha'
    ];
}
