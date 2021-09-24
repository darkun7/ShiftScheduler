<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{

    /**
     * index
     *
     * @param  Request $request
     * @return void
     */
    public function index(Request $request){
        $year = $request->query('year') ?? date('Y');
        $year = $year < 2020 ? date('Y'): $year;
        $schedules = generate_schedule($year);
        return view('schedule', compact('schedules', 'year'));
    }

    /**
     * index
     *
     * @param  Request $request
     * @return void
     */
    public function calendar(Request $request)
    {
        $month = $request->query('month') ?? null;
        $year = $request->query('year') ?? date('Y');
        $year = $year < 2020 ? date('Y'): $year;
        if( is_null($month) ){
            // Full Year Calendar
            $response['month'] = "full";
            for( $i=1; $i<=12; $i++ ){
               $response['data'][int_to_month($i)] = day_in_month($i,$year);
            }
        }else{
            // Specific Month Calendar
            $response['month'] = $month;
            $response['data'] = day_in_month($month,$year);
        }
        dd($response);

        return view('schedule');
    }
}
