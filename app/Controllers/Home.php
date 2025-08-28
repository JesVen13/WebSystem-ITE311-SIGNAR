<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('home'); // your normal home page
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function myhome() // NEW METHOD
    {
        return view('MyHome'); // This is your custom MyHome.php view
    }
}
