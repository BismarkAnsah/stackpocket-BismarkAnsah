 /**
   * Algorithm that returns a unit amount from the defined list 2, 1, 0.2, 0.1, 0.02, 0.01, 0.002 or 0.001 no matter number of bets selected.
   * @returns a unit amount in the list 2, 1, 0.2, 0.1, 0.02, 0.01, 0.002 or 0.001
   */
  calcUnitAmt() {
    /**Old Implementation */
    //   let multiplier, unitAmt;
    //   this.units.some(unit=>{
    //   multiplier =  this.calcActualAmt()/(this.totalBets*unit);
    //     if(+multiplier.toFixed(8)%1 == 0){
    //         unitAmt = unit;
    //         return true;
    //     }
    // });
    //     return unitAmt;

    /**New Implementation */
    let pseudoUnit = this.calcPseudoUnit();
    let decimalCount = this.decimalCount(pseudoUnit);
    let pseudoMult = this.fixArithmetic(pseudoUnit * 10 ** decimalCount);
    let realUnit =
      pseudoMult % 2 == 0 ? 2 * 10 ** -decimalCount : 10 ** -decimalCount;
    return realUnit;
  }