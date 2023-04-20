 _combinations_totalBets(...rowsAndSamples) {
    let rows = [],
      samples = [];
    //dividing rows and samples
    rowsAndSamples.forEach((element) => {
      Array.isArray(element) ? rows.push(element) : samples.push(element);
    });

    let row1Len = rows[0].length; // Get length of row1 Selections
    if (rows.length == 1) return
    this.getCombination(row1Len, samples[0]); // If there is only one row, return number of combinations for that row
    let row2Len = rows[1].length; // Get length of row2 Selections
    let row1Combinations = this.getCombination(row1Len, samples[0]); // Calculate number of combinations for first row
    let row2Combinations = this.getCombination(row2Len, samples[1]); // Calculate number of combinations for second row
    let totalCombinations = row1Combinations * row2Combinations; // Calculate total number of combinations
    let repeatedSelections = rows[1].filter((element) => rows[0].includes(element)).length; // Count the number of repeated selections between the two rows
    let combinationsWithoutRepeats = -1; //placeholder
    
    if (samples[0] == 1 && samples[1] == 1) {
      combinationsWithoutRepeats = row2Len * (row1Len - repeatedSelections) + repeatedSelections * (row2Len - 1); // if sample[0] and sample[1] are 1
    }else if (samples[0] != 1) { // If there is more than one selection in the first row
      let repeatsToRemove = this.getCombination(row1Len - 1, samples[0] - 1) * repeatedSelections; // Calculate number of combinations with repeated selections to remove
      combinationsWithoutRepeats = totalCombinations - repeatsToRemove; // Calculate number of combinations without repeats
    }else if(samples[1] != 1) { // If there is more than one selection in the second row
      let repeatsToRemove = this.getCombination(row2Len - 1, samples[1] - 1) * repeatedSelections; // Calculate number of combinations with repeated selections to remove
      combinationsWithoutRepeats = totalCombinations - repeatsToRemove; // Calculate number of combinations without repeats
    }
    return combinationsWithoutRepeats;
  }