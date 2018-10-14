const glob = require('glob-all');
const merge = require('webpack-merge');

const purgecssWordPress = require('./helpers/purgecss.wordpress');
const config = require('./config');

// explicit white list of classes
const whitelist = ['fade', 'show'];

// regex patterns to white list
const whitelistPatterns = [/^(fa|modal|text|bg|font|border|leading|tracking|object|cursor|d|align)-/];

// glob patterns for paths containing content
const content = ['resources/assets/scripts/**/*']
  .map(pattern => `${config.paths.root}/${pattern}`)
  .concat(config.patterns.html);

module.exports = merge(purgecssWordPress, {
  whitelist,
  whitelistPatterns,
  paths: glob.sync(content, { nodir: true }),
});
