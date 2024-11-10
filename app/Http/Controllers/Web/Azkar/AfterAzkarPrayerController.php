<?php

namespace App\Http\Controllers\Web\Azkar;

use App\Http\Controllers\Controller;
use App\Models\Azkar;
use App\Models\AzkarType;
use Illuminate\Http\Request;

class AfterAzkarPrayerController extends Controller
{

    public function index()
    {
        $AzkarType = AzkarType::query()->where('alias', '=', AzkarType::AFTER_PRATER_ALIAS)->first();
        $azkars = Azkar::query()->where('azkar_type_id', '=', $AzkarType['id'])->get();
        return view('web.azkar.eveningAzkar', compact('AzkarType', 'azkars'));
    }
}
