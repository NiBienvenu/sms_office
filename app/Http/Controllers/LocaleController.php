<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    //
    public function setLocale($locale){

        if (in_array($locale, config('locales.supported'))) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }
        return redirect()->back();
    }
}
