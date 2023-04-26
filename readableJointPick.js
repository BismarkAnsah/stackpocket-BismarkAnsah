let sum  = (arr)  =>  arr.reduce((acc, val) =>acc += val, 0)
let mult = (arr)  =>  arr.reduce((acc, val) =>acc *= val, 1)
 let jointPick = (pick, lengths) => {
   let res = getCombinations(lengths,pick);
   multiplied = res.map(val=>mult(val));
   return sum(multiplied);
 }