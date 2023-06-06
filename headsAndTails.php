function headsAndTails($luckyNumber, $selections)
{
    $paddedNumber = str_pad($luckyNumber, 2, '0', STR_PAD_LEFT);
    $outcomes = [];
    $headsSelections = $selections[0];
    $tailsSelections = $selections[1];

    // Extract the tens and ones positions
    $heads = (int)($paddedNumber / 10);
    $tails = $paddedNumber % 10;
    $headsWon = in_array($heads, $headsSelections);
    $tailsWon = in_array($tails, $tailsSelections);
    $outcomes[] =  $headsWon ? 'heads Won' : 'heads Lost';
    $outcomes[] =  $tailsWon ? 'tails Won' : 'tails Lost';
    if (!$headsWon && !$tailsWon) {
        $outcomes = [];
        $outcomes[] = 'both lost';
    }
    return $outcomes;
}