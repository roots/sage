var package = require('./package');
var semver = require('semver');

if(!semver.satisfies(process.version, package.engines.node)) {
  // Not sure if throw or process.exit is best.
  console.log('Required node version ' + package.engines.node + ' not satisfied with current version ' + process.version);
  process.exit(1);
}
