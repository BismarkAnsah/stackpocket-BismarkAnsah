  function pickAPlace(pick, selectionsCount, totalPlacesSelected)
  {
    return pick==3 ?permutations(selectionsCount, 2)*combinations(totalPlacesSelected, pick):combinations(selectionsCount, pick)*combinations(totalPlacesSelected, pick);
  }
