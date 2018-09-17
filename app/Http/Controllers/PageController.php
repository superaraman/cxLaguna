<?php

namespace App\Http\Controllers;

class PageController extends Controller 
{
    /**
     * Index of the Page
     */
    public function index()
    {
        return view('pages/home');
    }
}