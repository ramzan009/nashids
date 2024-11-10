<?php

namespace App\Http\Controllers\Web\Azkar;

use App\Http\Controllers\Controller;
use App\Models\Azkar;
use App\Models\AzkarType;
use Illuminate\Http\Request;

class MorningAzkarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AzkarType = AzkarType::query()->where('alias', '=', AzkarType::MORNING_ALIAS)->first();
        $azkars = Azkar::query()->where('azkar_type_id', '=', $AzkarType['id'])->get();
        return view('web.azkar.morningAzkar', compact('AzkarType', 'azkars'));
    }

}
