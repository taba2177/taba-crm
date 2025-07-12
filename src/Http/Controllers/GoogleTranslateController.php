<?php

namespace Taba\Crm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class GoogleTranslateController extends Controller
{
    //
    public function googleTranslate()
    {
        return view('welcome');
    }

    public function googleTranslateChange(Request $request)
    {
        App::setLocale($request->lang);

        Session::put('locale',$request->lang);

        return redirect()->back();
    }
}
