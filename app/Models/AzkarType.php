<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class AzkarType extends Model
{
    use HasFactory;
    use CrudTrait;
    use SoftDeletes;

    const MORNING_ALIAS = 'morning';
    const EVENING_ALIAS = 'evening';
    const AFTER_PRATER_ALIAS = 'afterPrayer';

    protected $table = 'azkar_types';

    protected $fillable = [
        'title',
        'alias'
    ];

    public function azkar(): HasMany
    {
        return $this->hasMany(Azkar::class, 'azkar_type_id', 'id');
    }

}
