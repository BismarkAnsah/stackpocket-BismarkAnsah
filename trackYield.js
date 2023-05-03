interface YieldBet {
  betId: string;
  estimatedDrawTime: string;
  nextDay: boolean;
  current: boolean;
  multiplier: number;
  betAmt: number;
  bonus: number;
  currentAmt: number;
  expectedProfit: number;
  percentageProfit: number;
}

interface YieldData {
  bets: YieldBet[];
  trackInfo: {
    total_draws: number;
    total_amt_bets: number;
    total_amt_to_pay: number;
  }
}

interface YieldBet {
  betId: string;
  estimatedDrawTime: string;
  nextDay: boolean;
  current: boolean;
  multiplier: number;
  betAmt: number;
  bonus: number;
  currentAmt: number;
  expectedProfit: number;
  percentageProfit: number;
}

interface YieldData {
  bets: YieldBet[];
  trackInfo: {
    total_draws: number;
    total_amt_bets: number;
    total_amt_to_pay: number;
  }
}

function createTrackYield(
  firstDrawDate: Date,
  betId: string,
  totalDraws: number,
  totalBets: number,
  startMultiplier: number,
  singleBetAmt: number,
  bonus: number,
  minimumYield: number = 50
): YieldData {
  const yieldData: YieldData = {
    bets: [],
    trackInfo: {
      total_draws: 0,
      total_amt_bets: 0,
      total_amt_to_pay: 0
    }
  };

  function createTrackYield(
    firstDrawDate: Date,
    betId: string,
    totalDraws: number,
    totalBets: number,
    startMultiplier: number,
    singleBetAmt: number,
    bonus: number,
    minimumYield: number = 50
  ): YieldData {
    const yieldData: YieldData = {
      bets: [],
      trackInfo: {
        total_draws: 0,
        total_amt_bets: 0,
        total_amt_to_pay: 0
      }
    };
  
    let previousBetAmt: number;
    let currentAmt: number;
    let currentDrawDate = firstDrawDate;
    let estimatedDrawTime = this.getDate(currentDrawDate) + " " + this.getTime(currentDrawDate);
    let currentBetId = betId;
    const constantBonus = bonus;
    const basicBonus = this.fixArithmetic(bonus * startMultiplier);
    const basicMultiplier = startMultiplier;
    const basicBetAmt = basicMultiplier * singleBetAmt;
    const basicCurrentAmt = basicBetAmt;
    const basicExpectedProfit = constantBonus - basicCurrentAmt; //expected profit for the first bet on track.
    const basicPercentageProfit = this.truncate(this.fixArithmetic(basicExpectedProfit / basicCurrentAmt * 100), 4); //profit percentage for the first bet on track
  
    /**
     * treating the first bet in track separately
     */
    yieldData.bets[0] = {
      betId: currentBetId,
      estimatedDrawTime: estimatedDrawTime,
      nextDay: this.isNextDay(estimatedDrawTime),
      current: this.isCurrent(currentBetId),
      multiplier: basicMultiplier,
      betAmt: basicBetAmt,
      bonus: basicBonus,
      currentAmt: basicCurrentAmt,
      expectedProfit: basicExpectedProfit,
      percentageProfit: basicPercentageProfit
    };
  
    /**
     * all the other elements in the track starts from here
     */
    let nextDrawDate: Date, i = 1;
    let multiplier: number, expectedProfit: number, percentageProfit: number, currentBonus: number, previousMultiplier: number;
    for (i; i < totalDraws; i++) {
      previousBetAmt = yieldData.bets[i - 1]["currentAmt"];
      previousMultiplier = yieldData.bets[i - 1]["multiplier"];
      multiplier = this.getYieldMultiplier(minimumYield, constantBonus, previousBetAmt, singleBetAmt); //generates the multiplier for the current bet in the track.
      multiplier = multiplier < previousMultiplier ? previousMultiplier : multiplier; //makes sure the generated multiplier isn't less than the start multiplier.
      currentBonus = constantBonus * multiplier;
      currentAmt = this.fixArithmetic(previousBetAmt + (singleBetAmt * multiplier));
      expectedProfit = this.fixArithmetic(currentBonus - currentAmt);
      percentageProfit = this.truncate(this.fixArithmetic(expectedProfit / currentAmt * 100), 4);
      nextDrawDate = new Date(this.addMinutes(currentDrawDate, intervalMinutes));
      currentBetId = this.generateNextBetId(currentBetId, currentDrawDate, intervalMinutes);
      yieldData.bets[i] = {
        betId: currentBetId,
        estimatedDrawTime: estimatedDrawTime,
        nextDay: this.isNextDay(estimatedDrawTime),
        current: this.isCurrent(currentBetId),
        multiplier: multiplier,
        betAmt: this.fixArithmetic(multiplier * singleBetAmt),
        bonus: currentBonus,
        currentAmt: currentAmt,
        expectedProfit: expected
