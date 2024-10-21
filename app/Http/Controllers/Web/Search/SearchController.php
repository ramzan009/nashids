<?php

namespace App\Http\Controllers\Web\Search;

use App\Http\Controllers\Controller;
use App\Models\Nashid;
use App\Models\Quran;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $search = $request->search;

        $qurans = Quran::query()->where('title', 'LIKE', "%$search%")->get();
        $nashids = Nashid::query()->where('title', 'LIKE',  "%$search%")->get();

        return view('web.search.search', compact('qurans', 'nashids'));
    }
}
