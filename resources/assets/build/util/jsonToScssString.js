/**
 * Recurse through JSON object and process to SCSS.
 *
 * An internal method of `json-sass` modified to support
 * compass syntax.
 *
 * @link https://github.com/acdlite/json-sass
 */
module.exports = jsonToScssString = value => {
  function _jsonToScssString(value) {

    /**
     * Set indentation
     */
    const initialIndentLevel = arguments[1] === undefined ? 0 : arguments[1]
    let indentLevel = initialIndentLevel

    /**
     * Handle values
     */
    switch (typeof value) {

      case 'number':
        return value.toString()

      case 'string':
        return value

      case 'object':
        if (Array.isArray(value)) {
          var sassVals = (() => {
            var _sassVals = []

            for (
              let _iterator = value[Symbol.iterator](), _step;
              !(_step = _iterator.next()).done;

            ) {
              let v = _step.value
              if (isNotUndefined(v)) {
                _sassVals.push(_jsonToScssString(v, indentLevel))
              }
            }
            return _sassVals
          })()
          return `(${sassVals.join(', ')})`

        } else if (isNull(value)) {
          return

        } else if (isPlainObject(value)) {
          var _ret = (function() {
            indentLevel += 1
            var indent = indentsToSpaces(indentLevel)

            var jsObj = value
            var sassKeyValPairs = []

            sassKeyValPairs = Object.keys(jsObj).reduce(function(result, key) {
              var sassVal = _jsonToScssString(jsObj[key], indentLevel)

              if (isNotUndefined(sassVal)) {
                result.push(`"${key}": ${sassVal}`)
              }

              return result
            }, [])

            const result = `(\n${indent}${sassKeyValPairs.join(
              ',\n' + indent
            )}\n${indentsToSpaces(indentLevel - 1)})`

            indentLevel -= 1
            return {v: result}
          })()

          if (typeof _ret === 'object') {
            return _ret.v
          }
        } else {
          return value.toString()
        }

      default:
        return
    }
  }

  return _jsonToScssString(value)
}

/**
 * Helpers
 */
const isPlainObject = require('lodash-node/modern/objects/isObject')

const indentsToSpaces = indentCount => {
  return Array(indentCount + 1).join('  ')
}

const isNull = value => {
  return value === null
}

const isNotUndefined = value => {
  return typeof value !== 'undefined'
}
