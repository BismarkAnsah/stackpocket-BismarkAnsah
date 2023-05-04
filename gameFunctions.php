/**
 * Checks if all the given draw numbers exist in the selections.
 *
 * @param int[] $selections An array of selected numbers.
 * @param int[] $drawNumbers An array of draw numbers to check against.
 *
 * @return bool Returns true if all the draw numbers exist in the selections, false otherwise.
 */
function areAllDrawNumbersInSelection(array $selections, array $drawNumbers): bool
{
    // Checks if the number of draw numbers is equal to the number of unique draw numbers.
    if (count($drawNumbers) === count(array_unique($drawNumbers))) {
        // Checks if all the draw numbers exist in the selections.
        $allExists = !array_diff($drawNumbers, $selections);
        return boolval($allExists);
    }

    return false;
}

/**
 * Checks if an array of drawn numbers contains a specific number of duplicates and unique numbers,
 * and if those duplicates are present in the user's selection.
 *
 * @param array $drawNumbers An array of numbers representing the drawn numbers.
 * @param array $userSelections An array of two arrays representing the user's number selections.
 * @param int $mostRepeatingNumberCount The count of the most frequently repeated number in the drawNumbers array.
 * @param int $repeatingNumbersCount The expected count of repeating numbers in the drawNumbers array.
 * @param bool $row2Repeats Flag indicating whether row 2 in the user's selection can have repeated numbers.
 *
 * @return bool Returns true if the drawNumbers array contains the expected number of duplicates and unique
 * numbers and those duplicates are present in the user's selection. Returns false otherwise.
 */
function duplicatesAndUniques(
    array $drawNumbers,
    array $userSelections,
    int $mostRepeatingNumberCount,
    int $repeatingNumbersCount,
    bool $row2Repeats = false
) {
    // Extract the two rows from the user's selection.
    $row1 = $userSelections[0];
    $row2 = $userSelections[1];

    // Find the duplicates in the drawNumbers array.
    $duplicates = array_intersect(array_count_values($drawNumbers), [$mostRepeatingNumberCount]);

    // Check if the count of duplicates is the expected value.
    if (count($duplicates) !== $repeatingNumbersCount) {
        return false;
    }

    // Extract the duplicate element from the duplicates array.
    $duplicateElement = array_keys($duplicates);

    // Check if the duplicate element is present in row 1 of the user's selection.
    if (array_diff($duplicateElement, $row1)) {
        return false;
    }

    // Remove the duplicate element from the drawNumbers array if row 2 cannot have repeated numbers.
    $drawNumbers = $row2Repeats ? array_unique($drawNumbers) : $drawNumbers;

    // Find the remaining elements in the drawNumbers array.
    $remainingElements = array_diff($drawNumbers, $duplicateElement);

    // Check if all remaining elements are present in row 2 of the user's selection.
    if (!doAllExists($remainingElements, $row2)) {
        return false;
    }

    return true;
}