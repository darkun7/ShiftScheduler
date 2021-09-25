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
        $year  = $request->query('year') ?? date('Y');
        $month = $request->query('month') ?? date('n');
        $year  = $year < 2020 ? date('Y'): $year;

        $schedules = generate_schedule($year);
        return view('schedule', compact('schedules', 'year', 'month'));
    }

    public function find(Request $request){
        // Hit this url http://shifter.test/api/find?year=2045&month=12&day=27&group=D
        // to fetch data at Desember 27th 2045, Group D

        $year  = $request->query('year') ?? date('Y');
        $month = $request->query('month') ?? date('n');
        $day   = $request->query('day') ?? date('d');
        $group = $request->query('group') ?? null;
        $code = 400;
        if($year < 2020){
            $code = 400;
            $msg = "year must be greater than 2020";
        }

        $schedules = generate_schedule($year);
        if( is_null($group) ){
            $code = 200;
            $data = $schedules[int_to_month($month)][$day]["shift"];
            $msg = "Success fetch data shift on selected day";
        }else{
            $code = 200;
            $data = translate_pattern( $schedules[int_to_month($month)][$day]["shift"][$group] );
            $msg  = "Success fetch data of group $group on selected day";
        }
        $content = [
            "success" => $code==200? True:False,
            "data" => $data ?? null,
            "message" => $msg ?? null,
        ];
        return response($content, $code)->header('Content-Type', 'application/json');
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
        $year  = $request->query('year') ?? date('Y');
        $year  = $year < 2020 ? date('Y'): $year;
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
