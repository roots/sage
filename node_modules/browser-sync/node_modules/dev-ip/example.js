var devip = require("./lib/dev-ip");

var ip = devip.getIp(null);
console.log(ip);

var ipCli = devip.getIp("cli");
console.log(ip);