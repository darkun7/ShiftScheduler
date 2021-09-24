<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function to_time(Carbon $date) : string
    {
        return strtotime($date->toDateTimeString());
    }

    public function int_to_month(int $int) : string
    {
        return Carbon::create()->month($int)->isoFormat('MMMM');
    }

    public function day_in_month(int $month, int $year) : array
    {
        $result = [];
        $date = Carbon::create($year, $month, 1, 0);
        $dateTime = $this->to_time($date);
        $next = $this->to_time($date->addMonths());
        while ( $dateTime < $next ) {
            $day = Carbon::createFromTimestamp($dateTime);
            $data = [
                'day' => $day->isoFormat('dddd'),
                'date'=> $day->isoFormat('D MMMM YYYY')
            ];
            array_push($result, $data);
            $dateTime += 24*60*60;
        }
        return $result;
    }

    public function index(Request $request)
    {
        $month = $request->query('month') ?? null;
        $year = $request->query('year') ?? date('Y');
        $year = $year < 2020 ? date('Y'): $year;
        if( is_null($month) ){
            // Full Year Calendar
            $response['month'] = "full";
            for( $i=1; $i<=12; $i++ ){
               $response['data'][$this->int_to_month($i)] = $this->day_in_month($i,$year);
            }
        }else{
            // Specific Month Calendar
            $response['month'] = $month;
            $response['data'] = $this->day_in_month($month,$year);
        }
        dd($response);

        return view('schedule');
    }
}
