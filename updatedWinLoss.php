/**
 * Validates if user's lottery selections match the specified rules.
 *
 * @param array $drawNumbers An array containing the numbers drawn in the lottery game.
 * @param array $userSelections An array containing the user's selections for the lottery game.
 *                  The first element of the array represents the user's selections for the first row,
 *                  and the second element (if present) represents the user's selections for the second row.
 * @param int $row1LeastSelection The minimum number of selections required in the first row for a match to be considered valid.
 * @param int $eachRow1AppearCount The number of times a selection must appear in the drawn numbers for a match to be considered valid in the first row.
 * @param int $row2LeastSelection The minimum number of selections required in the second row (if present) for a match to be considered valid.
 * @param int $eachRow2AppearCount The number of times a selection must appear in the drawn numbers for a match to be considered valid in the second row (if present).
 *
 * @return bool Returns true if user's selections match the specified rules, false otherwise.
 */
function matchDrawRule(
    array $drawNumbers,
    array $userSelections,
    int $row1LeastSelection,
    int $eachRow1AppearCount,
    int $row2LeastSelection = 0,
    int $eachRow2AppearCount = 0
) {
 
    $row1 = $userSelections[0];
    $row2 = isset($userSelections[1]) ? $userSelections[1] : false;
   
    $row1MatchCount = array_keys(array_intersect(array_count_values($drawNumbers), [$eachRow1AppearCount]));
    $row1AllIn = array_intersect($row1, $row1MatchCount);
    
    // Check if user has selected the required minimum number of numbers in the first row.
    if (count($row1AllIn) !== $row1LeastSelection) {
        return false;
    }

    // If second row is present, validate user's selections for the second row.
    if (!$row2) {
        return true;
    }

    $remainingElements = array_diff($drawNumbers, $row1AllIn);

    $row2MatchCount = array_keys(array_intersect(array_count_values($remainingElements), [$eachRow2AppearCount]));
    $row2AllIn = array_intersect($row2, $row2MatchCount);
 
    // Check if user has selected the required minimum number of numbers in the second row.
    if (count($row2AllIn) !== $row2LeastSelection) {
        return false;
    }

    return true;
}