<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Quran extends Model
{
    use HasFactory;
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quran';

    protected $fillable = [
        'title',
        'author_id',
        'url'
    ];


    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

}
