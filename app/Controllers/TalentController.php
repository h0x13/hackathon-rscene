<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TalentController extends BaseController
{
    public function home()
    {
        // You can fetch events/books here and pass to the view if needed
        // $data['events'] = ...;
        return view('pages/talents/home');
    }

    public function events()
    {
        // You can fetch user events here and pass to the view if needed
        // $data['events'] = ...;
        return view('pages/talents/events');
    }

    public function profile(){
        return view('pages/talents/profile');
    }
}