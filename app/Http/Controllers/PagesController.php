<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function show404()
    {
        return view('pages.404');
    }

    public function showBlank()
    {
        return view('pages.blank');
    }

    public function showButtons()
    {
        return view('pages.buttons');
    }

    public function showCards()
    {
        return view('pages.cards');
    }

    public function showCharts()
    {
        return view('pages.charts');
    }

    public function showForgotPassword()
    {
        return view('pages.forgot-password');
    }

    public function showIndex()
    {
        return view('pages.index');
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function showTables()
    {
        return view('pages.tables');
    }

    public function showUtilitiesAnimation()
    {
        return view('pages.utilities-animation');
    }

    public function showUtilitiesBorder()
    {
        return view('pages.utilities-border');
    }

    public function showUtilitiesColor()
    {
        return view('pages.utilities-color');
    }

    public function showUtilitiesOther()
    {
        return view('pages.utilities-other');
    }
}
