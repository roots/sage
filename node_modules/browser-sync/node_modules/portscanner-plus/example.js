var portScanner = require("./lib/index");

// Return named ports as object
//portScanner.getPorts(3, 3000, 4000, ['controlPanel', 'socket', 'client']).then(function (ports) {
//    console.log(ports);
//});

// Return an array of ports
portScanner.getPorts(3, 3000, 3001).then(function (ports) {
    console.log(ports);
}, function (error) {
    console.log(error);
});