<?php

namespace App\Http\Controllers;

/**
 * The controller for all views of the front-end implementation of the API.
 */
class PagesController extends Controller
{
    public function getIndex()
    {
        return view ('pages.welcome');
    }

    public function getIntro()
    {
        return view('pages.intro');
    }

    public function getAirportsPage()
    {
        return view('pages.airports');
    }
}
