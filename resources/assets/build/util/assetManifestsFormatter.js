const path = require('path');

module.exports = (key, value) => {
  if (typeof value === 'string') {
    return value;
  }
  const manifest = value;
  /**
   * Hack to prepend scripts/ or styles/ to manifest keys
   *
   * This might need to be reworked at some point.
   *
   * Before:
   *   {
   *     "main.js": "scripts/main_abcdef.js"
   *     "main.css": "styles/main_abcdef.css"
   *   }
   * After:
   *   {
   *     "scripts/main.js": "scripts/main_abcdef.js"
   *     "styles/main.css": "styles/main_abcdef.css"
   *   }
   */
  Object.keys(manifest).forEach(src => {
    const sourcePath = path.basename(path.dirname(src));
    const targetPath = path.basename(path.dirname(manifest[src]));
    if (sourcePath === targetPath) {
      return;
    }
    manifest[`${targetPath}/${src}`] = manifest[src];
    delete manifest[src];
  });
  return manifest;
};
