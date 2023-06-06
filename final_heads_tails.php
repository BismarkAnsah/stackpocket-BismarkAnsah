function headsAndTails($luckyNumber, $selections)
{
    $paddedNumber = str_pad($luckyNumber, 2, '0', STR_PAD_LEFT);
    $outcomes = [];
    $heads = (int) ($paddedNumber / 10);
    $tails = $paddedNumber % 10;
    $headsWon = in_array($heads, $selections[0]);
    $tailsWon = in_array($tails, $selections[1]);

    if ($headsWon && $tailsWon) {
        $outcomes[] = 'both won';
    } elseif (!$headsWon && !$tailsWon) {
        $outcomes[] = 'both lost';
    } else {
        $outcomes[] = $headsWon ? 'heads Won' : 'heads Lost';
        $outcomes[] = $tailsWon ? 'tails Won' : 'tails Lost';
    }

    return $outcomes;
}