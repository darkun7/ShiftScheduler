<?php
use Carbon\Carbon;

if (!function_exists('int_to_month')) {
    /**
     * int_to_month
     *
     * @param  int $int
     * @return string
     */
    function int_to_month(int $int) : string
    {
        return Carbon::create()->month($int)->isoFormat('MMMM');
    }
}

if (!function_exists('day_to_num')) {
    function day_to_num(string $day) : int
    {
        $array = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
        return array_search($day, $array);
    }
}

if (!function_exists('array_cyclical')) {
    function array_cyclical(int $int, array $array) : string
    {
        $len = count($array);
        if(isset($array[$int])) {
            return $array[$int];
        }
        return $array[$len-$int];
    }
}


if (!function_exists('day_in_month')) {
    /**
     * day_in_month
     *
     * @param  int $month
     * @param  int $goal
     * @return array
     */
    function day_in_month(int $month, int $goal) : array
    {
        $result = [];
        $date = Carbon::create($goal, $month, 1, 0);
        $dateTime = $date->timestamp;
        $next = $date->addMonths()->timestamp;
        while ( $dateTime < $next ) {
            $day = Carbon::createFromTimestamp($dateTime);
            $data = [
                'day_name' => $day->isoFormat('dddd'),
                'date'=> $day->isoFormat('D MMMM YYYY')
            ];
            array_push($result, $data);
            $dateTime += 24*60*60;
        }
        return $result;
    }
}

if (!function_exists('generate_schedule')) {
    /**
     * generate_schedule
     *
     * @param  int $year
     * @return array
     */
    function generate_schedule(int $year) : array
    {
        $initial = next_pattern($year);
        $groupA = config('shiftpattern.GroupA');
        $groupB = config('shiftpattern.GroupB');
        $groupC = config('shiftpattern.GroupC');
        $groupD = config('shiftpattern.GroupD');
        if ( count($groupA) !=
             count($groupB) &&
             count($groupC) !=
             count($groupD) ) dd("Pattern length doesn't same");
        $result = [];
        $date = Carbon::create($year, 1, 1, 0); //January 1st xxxx
        $dateTime = $date->timestamp;
        $next = $date->addYears()->timestamp;

        $shiftIdx = $initial;
        while ( $dateTime < $next ) {
            $day = Carbon::createFromTimestamp($dateTime);
            $d = $day->isoFormat('D');
            $m = $day->isoFormat('MMMM');
            $data = [
                'day_name' => $day->isoFormat('dddd'),
                'date'=> $day->isoFormat('D MMMM YYYY'),
                'shift' => [
                    "A" => $groupA[$shiftIdx],
                    "B" => $groupB[$shiftIdx],
                    "C" => $groupC[$shiftIdx],
                    "D" => $groupD[$shiftIdx]
                ]
            ];
            $result[$m][$d] = $data;
            $shiftIdx +=1;
            $shiftIdx = $shiftIdx >= 28? 0 : $shiftIdx;
            $dateTime += 24*60*60;
        }
        return $result;
    }
}

if (!function_exists('leap')) {
    /**
     * leap_year
     *
     * @param  int $goal
     * @param  int $start
     * @return int
     */
    function leap_year(int $goal,int $start = null) : int
    {
        if(is_null($start)) {
            $start = date('Y');
        }
        if( $goal <= $start ){
            dd("Must be Greater than $start");
        }
        $leap = 0;
        for( $i = $start; $i< $goal; $i++ ){
            if($i % 4==0){
                if($i%100 == 0){
                    if($i%400 == 0){
                        $leap+=1;
                    }
                }else{
                    $leap+=1;
                }
            }
        }
        return $leap;
    }
}

if (!function_exists('next_pattern')) {
    /**
     * next_pattern
     *
     * @param  int $year
     * @return int
     */
    function next_pattern(int $year) : int
    {
        //Configuration of started pattern
        $first_pattern = [
            "year" => 2020,
            "idx"  => 3
        ];
        if( $year <= 2020 ){
            dd("Must be Greater than 2020");
        }
        $leap = $first_pattern["idx"]+leap_year($year,2020);
        $diff = $year - $first_pattern["year"];
        return $diff+$leap;
    }
}

if (!function_exists('translate_pattern')) {

    function translate_pattern(string $item)
    {
        switch ($item){
            case "p":
                return "Pagi";
                break;
            case "s":
                return "Siang";
                break;
            case "m":
                return "Malam";
                break;
            case "-":
                return "Off";
                break;
            default:
                return $item;
        }
    }
}
