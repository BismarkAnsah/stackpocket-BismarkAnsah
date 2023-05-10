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