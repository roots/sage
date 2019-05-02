const fs = require('fs')
const jsonToScssString = require('./jsonToScssString')

const json = require(__dirname + '/../../editor.json')
const scss = `${__dirname}/../../styles/components/wp-blocks/base/_generated.scss`

class JsonToScssPlugin {
  apply() {
    fs.writeFile(
      scss,
      `$wp-editor: ${jsonToScssString(json.styles)};\n`,
      (err => { err && console.log(err) })
    )
  }
}

module.exports = JsonToScssPlugin
