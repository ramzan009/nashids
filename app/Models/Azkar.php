<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Azkar extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'azkars';

    protected $fillable = [
        'title',
        'azkar_type_id',
        'content_arabic',
        'content_rus',

    ];

    public function azkarType()
    {
        return $this->belongsTo(AzkarType::class, 'azkar_type_id');
    }


}
