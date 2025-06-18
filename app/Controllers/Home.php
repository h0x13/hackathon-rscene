<?php

namespace App\Controllers;
use App\Models\EventPerformance;

class Home extends BaseController
{
    public function index()
    {

        $eventModel = new EventPerformance();
        $eventForMap = $eventModel
            ->select('
                event_performance.id,
                event_performance.event_name,
                event_performance.event_description,
                event_performance.event_startdate,
                event_performance.event_status,
                vp.lat,
                vp.lon as lng,
                v.street,
                v.barangay,
                v.city,
                v.zip_code,
                v.rent
            ')
            ->join('venue v', 'v.id = event_performance.venue_id', 'left')
            ->join('venue_pin vp', 'v.pin_id = vp.id', 'left')
            ->where('event_performance.event_startdate >=', date('Y-m-d'))
            ->orderBy('event_performance.event_startdate', 'ASC')
            ->findAll();

        $data = [
            'eventForMap' => $eventForMap,
        ];


        return view('pages/talents/homepage_final', $data);
    }
}
