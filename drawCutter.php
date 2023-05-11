function drawCutter(array $drawNumbers, string $cutPhrase): array
{
    $lowerCaseCutPhrase = strtolower($cutPhrase);
    $string = preg_replace('/[^a-z]/', '', $lowerCaseCutPhrase);
    $integer = (int)preg_replace('/[^0-9]/', '', $lowerCaseCutPhrase);

    switch ($string) {
        case 'first':
            $extractedDrawNumbers = array_slice($drawNumbers, 0, $integer);
            break;
        case 'last':
            $extractedDrawNumbers = array_slice($drawNumbers, -$integer);
            break;
        case 'middle':
        case 'mid':
            $drawSize = count($drawNumbers);
            $start = floor($drawSize / 2) - floor($integer / 2);
            $extractedDrawNumbers = array_slice($drawNumbers, $start, $integer);
            break;
        default:
            $extractedDrawNumbers = $drawNumbers;
    }
    return $extractedDrawNumbers;
}