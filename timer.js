<?php
date_default_timezone_set("Asia/Shanghai");

// function generateRandom5()
// {
//     $result = [];
//     for ($i = 0; $i < 5; $i++)
//         array_push($result, random_int(0, 9));
//     return $result;
// }

// $randoms = implode(",", generateRandom5());

// $data = array(
//     "id" => random_int(100, 5000),
//     "numbers" => generateRandom5(),
//     "betId" => "202302160013",
//     "dateTime" => "2023-02-16 00:12:00",
//     "timeLeft" => 20
// );
// $data2 = '{"0":{"id":"5674","draw_count":"1372","draw_date":"202303021372","draw_number":"7,6,7,1,6","draw_time":"2023-03-02 23:51:00","draw_datetime":"2023-03-02 23:50:59"},"1":{"id":"5675","draw_count":"1373","draw_date":"202303021373","draw_number":"8,9,2,2,7","draw_time":"2023-03-02 23:52:00","draw_datetime":"2023-03-02 23:51:59"},"2":{"id":"5676","draw_count":"1374","draw_date":"202303021374","draw_number":"9,3,7,6,1","draw_time":"2023-03-02 23:53:00","draw_datetime":"2023-03-02 23:52:59"},"3":{"id":"5677","draw_count":"1375","draw_date":"202303021375","draw_number":"5,5,2,6,9","draw_time":"2023-03-02 23:54:00","draw_datetime":"2023-03-02 23:53:59"},"4":{"id":"5678","draw_count":"1376","draw_date":"202303021376","draw_number":"6,0,6,2,8","draw_time":"2023-03-02 23:55:00","draw_datetime":"2023-03-02 23:54:59"},"5":{"id":"5679","draw_count":"1377","draw_date":"202303021377","draw_number":"7,3,9,7,7","draw_time":"2023-03-02 23:56:00","draw_datetime":"2023-03-02 23:55:59"},"6":{"id":"5680","draw_count":"1378","draw_date":"202303021378","draw_number":"9,1,6,3,0","draw_time":"2023-03-02 23:57:00","draw_datetime":"2023-03-02 23:56:59"},"7":{"id":"5681","draw_count":"1379","draw_date":"202303021379","draw_number":"5,3,0,9,1","draw_time":"2023-03-02 23:58:00","draw_datetime":"2023-03-02 23:57:59"},"8":{"id":"5682","draw_count":"1380","draw_date":"202303021380","draw_number":"1,0,8,7,6","draw_time":"2023-03-02 23:59:00","draw_datetime":"2023-03-02 23:58:59"},"9":{"id":"' . random_int(100, 5000) . '","draw_count":"1","draw_date":"202303020001","draw_number":"' . $randoms . '","draw_time":"2023-03-03 00:00:00","draw_datetime":"2023-03-02 23:59:59"},"timeLeft":' . (60 - date("s")) . '}';
// echo $data2;
//  function getDifferenceInSecs($time1, $time2 = true)
//     {
//         $start = new DateTime($time1);
//         $end = $time2 === true ? new DateTime() : new DateTime($time2);
//         //  print_r($end);die;
//         $diff = $start->diff($end);
//         $diffInSecs = $diff->d * 24 * 60 * 60 + $diff->h * 60 * 60 + $diff->i * 60 + $diff->s;
//         return $diffInSecs;
//     }

//     $res = getDifferenceInSecs("2023-03-22");
//     echo $res;
const DEFAULT_INTERVAL = 90; //seconds
const GAME_START = "00:00:00";
 const GAME_END = "24:00:00";
 const GAP_START = "04:59:00"; //1 hour gap
 const GAP_END = "06:00:00";

    /**
     * the number of seconds between 12 am today and the @$time provided
     *
     * @param string $time the time to calculate the number of seconds up to.
     * @param string $start where to start calculating difference. default is 12 am today
     * @return string the difference in seconds
     */
    function getSecondsElapsed($time, $start = "today")
    {
        $dateTime = DateTime::createFromFormat("H:i:s", $time);
        $timeElapsed = $dateTime->getTimestamp() - strtotime($start);
        return $timeElapsed;
    }

    /**
     * gets the next draw datetime that comes immediately after the "$time" provided
     *
     * @param string  $time the datetime or time to start calculating from.
     * @return string the next closest draw datetime to the "$time" provided
     */
    function getNextDrawTime($gap_start, $gap_end, $defaultInterval, $game_end, $game_start, $time = "now")
    {
        $time = Date("H:i:s", strtotime($time));
        //if the time requested is still between the gap ie if gap hasn't ended.
        if (($gap_start and $gap_end) and $time >= $gap_start and $time < $gap_end) {
            return date("Y-m-d") . ' ' . $gap_end;
        }

        //if the last data has been drawn
        if ($time >=  $game_end) {
            $nextDay = strtotime("+$defaultInterval seconds", strtotime($game_end));
            return date("Y-m-d", $nextDay) . ' ' . $game_start;
        }

        //round the time up to the nearest draw time
        $timeElapsed = getSecondsElapsed($time, $game_start);
        $surplusSeconds = $timeElapsed % $defaultInterval;
        $nextDrawInSecs = $timeElapsed - $surplusSeconds + $defaultInterval;
        $nextDrawTimestamp = $nextDrawInSecs + strtotime($game_start);
        return date("Y-m-d H:i:s", $nextDrawTimestamp);
    }

        /**
     * gets the number of seconds between two dates.
     * this function only works if the difference in dates does not exceed 30 days;
     *
     * @param string $time1 the first date time
     * @param mixed $time2 the second date time. if this isn't provided, current datetime will be used.
     * @return int the number of seconds between the two dates provided.
     */
 function getDifferenceInSecs($time1, $time2 = true)
    {
        $start = new DateTime($time1);
        $end = $time2 === true ? new DateTime() : new DateTime($time2);
        $diff = $start->diff($end);
        $diffInSecs = $diff->d * 24 * 60 * 60 + $diff->h * 60 * 60 + $diff->i * 60 + $diff->s;
        return $diffInSecs;
    }

    $any = getNextDrawTime(GAP_START, GAP_END, DEFAULT_INTERVAL, GAME_END, GAME_START);
    $diff = getDifferenceInSecs($any);
    echo $diff;
