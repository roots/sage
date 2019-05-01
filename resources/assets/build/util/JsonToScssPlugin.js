const fs = require('fs')
const jsToSassString = require('json-sass/lib/jsToSassString.js')

const json = require(__dirname + '/../../editor.json')
const scss = __dirname + '/../../styles/components/wp-blocks/base/_generated.scss'

class JsonToScssPlugin {
  apply() {
    fs.writeFile(scss, this.doOutput(), err => {
      if (err) console.log(err)
    })
  }

  doOutput() {
    return `$wp-editor: ${jsToSassString(json)};\n`
  }

}

module.exports = JsonToScssPlugin
