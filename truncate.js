  /**
   * Truncates decimal. chops without rounding
   * @param {number} number data to truncate
   * @param {number} decimalPlaces number of decimal places to truncate
   * @returns truncated number
   */
  truncate(number, decimalPlaces = 3) {
    let indexOfDecimal = number.toString().indexOf(".");
    if (indexOfDecimal == -1) return number;
    let result = +number
      .toString()
      .slice(0, indexOfDecimal + (decimalPlaces + 1));
    return parseFloat(result);
  }