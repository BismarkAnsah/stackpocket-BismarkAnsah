let groupSum = (num) => {
  num = num>13?27-num:num;
  let loopLen = Math.ceil(num / 3);
  let counter = 0;
  for (let i = 0; i < loopLen; i++) {
    let min = ~~((num - i) / 2);
    let max = Math.ceil((num - i) / 2);
    for(;min>=i && max<=9;--min,++max)
        counter +=1;
  }
  return counter;
}