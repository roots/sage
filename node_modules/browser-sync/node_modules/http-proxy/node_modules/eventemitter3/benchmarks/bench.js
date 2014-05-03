'use strict';

var EventEmitter = require('../')
  , ee = new EventEmitter();

ee.on('data', function (data) {
  // hardcore number crunching
  var amount = (data + data) - data * 2 / 2;
  return amount;
});

//
// Benchmark configuration:
//
var NR_OF_RUNS = +process.env.NR_OF_RUNS || 50000
  , i, dstart, dend, start, end;

//
// Make hot
//
//for (i = 0; i < NR_OF_RUNS; i++) {
//  ee.emit('data', i, i, i, i, i, i, i);
//  ee.emits('data', i, i, i, i, i, i, i);
//}

//
// The actual run of the optimized function
//
for (i = 0, dstart = process.hrtime(); i < NR_OF_RUNS; i++) {
  ee.emit('data', i, i, i, i, i, i, i);
}

dend = process.hrtime(dstart);

for (i = 0, start = process.hrtime(); i < NR_OF_RUNS; i++) {
  ee.emits('data', i, i, i, i, i, i, i);
}
end = process.hrtime(start);
console.log('Old: ', dend[1], 'nano sec spend doing', NR_OF_RUNS, 'runs in total');
console.log('New: ', end[1], 'nano sec spend doing', NR_OF_RUNS, 'runs in total');
console.log('new is ', dend[1] - end[1], 'nano sec faster');
