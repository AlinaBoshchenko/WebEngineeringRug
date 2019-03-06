<?php

namespace App\Http\Controllers;


class PagesController extends Controller
{
    public function getIndex()
    {
        return view('pages.welcome');
    }

    public function getAirports()
    {
        $airports = [];
        $airports[] = [
            'airport_name' => 'John F. Kennedy International Airport',
            'airport_code' => 'JFK',
            'link' => '/airports/JFK'
        ];
        $airports[] = [
            'airport_name' => 'Los Angeles International Airport',
            'airport_code' => 'LAX',
            'link' => '/airports/LAX'
        ];

        $json_airports = [];

        foreach ($airports as $airport) {
            $json_airports[] = json_encode($airport);
        }

        return json_encode($airports);
    }

    public function getAbout()
    {
        return json_encode([
            "hello", "this",

        ]);
        $first = 'Henry';
        $last = 'Salas';
        $full = $first . ' ' . $last;

        return view('pages.about')->with("fullname", $full);
    }


    public function getContact()
    {
        return view('pages.contact');
    }
}
