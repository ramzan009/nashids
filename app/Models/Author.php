<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory;
    use HasFactory;
    use CrudTrait;
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];


    public function author()
    {
        return $this->hasMany(Quran::class, 'author_id');
    }
}
