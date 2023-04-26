let sum  = (arr)  =>  arr.reduce((acc, val) =>acc += val, 0)
let mult = (arr)  =>  arr.reduce((acc, val) =>acc *= val, 1)
let oneLineJointPick = (pick, lengths) => sum(getCombinations(lengths,pick).map(val=>mult(val)))
