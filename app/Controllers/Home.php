<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('home'); //home page
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function myLogin() 
    {
        return view('MyLogin'); //MyLogin.php 
    }
}
