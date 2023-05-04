/**
 * Checks if all the values in the first array exist in the second array.
 * this function checks for values without focusing on keys.
 * 
 * @param mixed[] $array1 An array of values to check.
 * @param mixed[] $array2 An array to check against.
 *
 * @return bool Returns true if all the values in $array1 exist in $array2, false otherwise.
 */
function doAllExists($array1, $array2): bool
{
    $array2Copy = $array2;
    foreach ($array1 as $value) {
        if (($keyFound = array_search($value, $array2Copy)) !== false) {
            unset($array2Copy[$keyFound]);
        } else {
            return false;
        }
    }
    return true;
}