function dragonTigerTie($type, $drawNumbers, $firstBallIndex, $secondBallIndex)
{

    $lowerCaseType = strtolower($type);
    $win = false;
    switch ($lowerCaseType) {
        case "dragon":
            $win = $drawNumbers[$firstBallIndex] > $drawNumbers[$secondBallIndex];
            break;
        case "tiger":
            $win = $drawNumbers[$firstBallIndex] < $drawNumbers[$secondBallIndex];
            break;
        case "tie":
            $win = $drawNumbers[$firstBallIndex] == $drawNumbers[$secondBallIndex];
            break;
    }
    return $win;
}