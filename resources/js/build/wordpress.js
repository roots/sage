import {
  defaultRequestToExternal,
  defaultRequestToHandle,
} from '@wordpress/dependency-extraction-webpack-plugin/lib/util'
import fs from 'fs'
import path from 'path'

/**
 * Vite plugin that handles WordPress dependencies and generates a dependency manifest.
 *
 * This plugin:
 * 1. Transforms @wordpress/* imports into global wp.* references
 * 2. Tracks WordPress script dependencies
 * 3. Generates an editor.deps.json file listing all WordPress dependencies
 *
 * @returns {import('vite').Plugin} Vite plugin
 */
export function wordpressPlugin() {
  const dependencies = new Set()

  // Helper functions for import handling
  function extractNamedImports(imports) {
    const match = imports.match(/{([^}]+)}/)
    if (!match) return []
    return match[1]
      .split(',')
      .map((s) => s.trim())
      .filter((s) => s !== '')
  }

  function handleNamedReplacement(namedImports, external) {
    return namedImports
      .map((imports) => {
        const [name, alias = name] = imports
          .split(' as ')
          .map((script) => script.trim())
        return `const ${alias} = ${external.join('.')}.${name};`
      })
      .join('\n')
  }

  function handleReplacements(imports, external) {
    const importStr = Array.isArray(imports) ? imports[0] : imports

    if (importStr.includes('{')) {
      const namedImports = extractNamedImports(importStr)
      return handleNamedReplacement(namedImports, external)
    }

    if (importStr.includes('* as')) {
      const match = importStr.match(/\*\s+as\s+(\w+)/)
      if (!match) return ''
      const alias = match[1]
      return `const ${alias} = ${external.join('.')};`
    }

    const name = importStr.trim()
    return `const ${name} = ${external.join('.')};`
  }

  return {
    name: 'wordpress-plugin',
    enforce: 'pre',
    config(config) {
      return {
        ...config,
        resolve: {
          ...config.resolve,
          alias: {
            ...config.resolve?.alias,
          }
        },
      }
    },
    resolveId(id) {
      if (id.startsWith('@wordpress/')) {
        const pkg = id.replace('@wordpress/', '')
        const external = defaultRequestToExternal(id)
        const handle = defaultRequestToHandle(id)

        if (external && handle) {
          dependencies.add(handle)
          return {
            id,
            external: true,
          }
        }
      }
    },
    transform(code, id) {
      if ((!id.endsWith('.js')) && !id.endsWith('.jsx') && !id.endsWith('.ts') && !id.endsWith('.tsx')) return

      const imports = [
        ...(code.match(/^import[\s\n]+(?:[^;]+?)[\s\n]+from[\s\n]+['"]@wordpress\/[^'"]+['"]/gm) || []),
        ...(code.match(/^import[\s\n]+['"]@wordpress\/[^'"]+['"]/gm) || []),
      ]

      imports.forEach((statement) => {
        const match =
          statement
            .replace(/[\s\n]+/g, ' ')
            .match(/^import (.+) from ['"]@wordpress\/([^'"]+)['"]/) ||
          statement.match(/^import ['"]@wordpress\/([^'"]+)['"]/);

        if (!match) return

        const [, imports, pkg] = match
        if (!pkg) return

        const external = defaultRequestToExternal(`@wordpress/${pkg}`)
        const handle = defaultRequestToHandle(`@wordpress/${pkg}`)

        if (external && handle) {
          dependencies.add(handle)
          const replacement = imports
            ? handleReplacements(imports, external)
            : `const ${pkg.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase())} = ${external.join('.')};`

          code = code.replace(statement, replacement)
        }
      })

      return { code, map: null }
    },
    generateBundle() {
      this.emitFile({
        type: 'asset',
        name: 'editor.deps.json',
        fileName: 'editor.deps.json',
        source: JSON.stringify([...dependencies]),
      })
    },
  }
}

/**
 * Rollup plugin that configures external WordPress dependencies.
 *
 * This plugin:
 * 1. Marks all @wordpress/* packages as external dependencies
 * 2. Maps external @wordpress/* imports to wp.* global variables
 *
 * This prevents WordPress core libraries from being bundled and ensures
 * they are loaded from WordPress's global scope instead.
 *
 * @returns {import('rollup').Plugin} Rollup plugin
 */
export function wordpressRollupPlugin() {
  return {
    name: 'wordpress-rollup-plugin',
    options(opts) {
      opts.external = (id) => id.startsWith('@wordpress/')
      opts.output = opts.output || {}
      opts.output.globals = (id) => {
        if (id.startsWith('@wordpress/')) {
          const packageName = id.replace('@wordpress/', '')
          return `wp.${packageName.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase())}`
        }
      }
      return opts
    },
  }
}

/**
* Vite plugin that generates a WordPress theme.json file based on Tailwind v4 CSS variables.
* This allows theme.json settings to stay in sync with your Tailwind design tokens.
*
* CSS variables defined in an @theme block will be transformed into theme.json format:
*
* @example
* ```css
* @theme {
*   --color-primary: #000000; -> { name: "primary", color: "#000000" }
*   --color-red-500: #ef4444; -> { name: "red-500", color: "#ef4444" }
*   --font-inter: "Inter";      -> { name: "inter", fontFamily: "Inter" }
*   --text-lg: 1.125rem;        -> { name: "lg", size: "1.125rem" }
* }
* ```
*
* @param {Object} options Plugin options
* @param {Object} options.tailwindConfig - The resolved Tailwind configuration object
* @param {boolean} [options.disableTailwindColors=false] - Disable including Tailwind colors in theme.json
* @param {boolean} [options.disableTailwindFonts=false] - Disable including Tailwind fonts in theme.json
* @param {boolean} [options.disableTailwindFontSizes=false] - Disable including Tailwind font sizes in theme.json
* @returns {import('vite').Plugin} Vite plugin
*/
export function wordpressThemeJson({
  tailwindConfig,
  disableTailwindColors = false,
  disableTailwindFonts = false,
  disableTailwindFontSizes = false,
}) {
  let cssContent = null

  /**
   * Safely extracts content between matched braces, handling:
   * - Nested braces
   * - String literals (both single and double quotes)
   * - CSS comments
   * - Escaped characters
   */
  function extractThemeContent(css) {
    const themeMatch = css.match(/@(?:layer\s+)?theme\s*{/s)
    if (!themeMatch) {
      return null // No @theme block - that's fine
    }

    const startIndex = themeMatch.index + themeMatch[0].length
    let braceCount = 1

    for (let i = startIndex; i < css.length; i++) {
      // Skip escaped characters
      if (css[i] === '\\') {
        i++
        continue
      }

      // Skip string literals
      if (css[i] === '"' || css[i] === "'") {
        const quote = css[i]
        i++
        while (i < css.length) {
          if (css[i] === '\\') {
            i++
          } else if (css[i] === quote) {
            break
          }
          i++
        }
        if (i >= css.length) {
          throw new Error('Unclosed string literal in CSS')
        }
        continue
      }

      // Skip CSS comments
      if (css[i] === '/' && css[i + 1] === '*') {
        i += 2
        while (i < css.length) {
          if (css[i] === '*' && css[i + 1] === '/') {
            i++
            break
          }
          i++
        }
        if (i >= css.length) {
          throw new Error('Unclosed comment in CSS')
        }
        continue
      }

      if (css[i] === '{') braceCount++
      if (css[i] === '}') braceCount--

      if (braceCount === 0) {
        return css.substring(startIndex, i)
      }
    }

    throw new Error('Unclosed @theme block - missing closing brace')
  }

  return {
    name: 'wordpress-theme-json',
    enforce: 'post',

    transform(code, id) {
      if (id.includes('app.css')) {
        cssContent = code
      }
      return null
    },

    async generateBundle() {
      if (!cssContent) {
        return // No CSS file to process
      }

      try {
        const baseThemeJson = JSON.parse(
          fs.readFileSync(path.resolve('./theme.json'), 'utf8')
        )

        const themeContent = extractThemeContent(cssContent)
        if (!themeContent) {
          return // No @theme block to process
        }

        // Process any CSS variables in whatever format they exist
        const colorVariables = {}
        const colorVarRegex = /--color-([^:]+):\s*([^;}]+)[;}]?/g
        let match

        while ((match = colorVarRegex.exec(themeContent)) !== null) {
          const [, name, value] = match
          colorVariables[name] = value.trim()
        }

        // Transform colors to theme.json format
        const colors = []
        Object.entries(colorVariables).forEach(([name, value]) => {
          if (name.endsWith('-*')) return

          if (name.includes('-')) {
            const [colorName, shade] = name.split('-')
            if (shade && !isNaN(shade)) {
              colors.push({
                name: `${colorName}-${shade}`,
                slug: `${colorName}-${shade}`.toLowerCase(),
                color: value,
              })
            } else {
              colors.push({
                name: name,
                slug: name.toLowerCase(),
                color: value,
              })
            }
          } else {
            colors.push({
              name: name,
              slug: name.toLowerCase(),
              color: value,
            })
          }
        })

        // Process any font families
        const fontFamilies = []
        const fontVarRegex = /--font-([^:]+):\s*([^;}]+)[;}]?/g
        while ((match = fontVarRegex.exec(themeContent)) !== null) {
          const [, name, value] = match
          // Skip feature settings, variation settings, and any font-* properties
          if (!name.includes('feature-settings') &&
              !name.includes('variation-settings') &&
              !['family', 'size', 'smoothing', 'style', 'weight', 'stretch']
                .some(prop => name.includes(prop))) {
            fontFamilies.push({
              name: name,
              slug: name.toLowerCase(),
              fontFamily: value.trim(),
            })
          }
        }

        // Process any font sizes
        const fontSizes = []
        const fontSizeVarRegex = /--text-([^:]+):\s*([^;}]+)[;}]?/g
        while ((match = fontSizeVarRegex.exec(themeContent)) !== null) {
          const [, name, value] = match
          // Skip line-height entries
          if (!name.includes('line-height')) {
            fontSizes.push({
              name: name,
              slug: name.toLowerCase(),
              size: value.trim(),
            })
          }
        }

        // Build theme.json with whatever variables were found
        const themeJson = {
          __processed__: "This file was generated from Tailwind v4 CSS variables",
          ...baseThemeJson,
          settings: {
            ...baseThemeJson.settings,
            ...((!disableTailwindColors && colors.length > 0) && {
              color: {
                ...baseThemeJson.settings?.color,
                palette: colors,
              },
            }),
            typography: {
              defaultFontSizes: false,
              customFontSize: false,
              ...((!disableTailwindFonts && fontFamilies.length > 0) && {
                fontFamilies,
              }),
              ...(!disableTailwindFontSizes && fontSizes.length > 0 && {
                fontSizes,
              }),
            },
          },
        }

        delete themeJson.__preprocessed__

        this.emitFile({
          type: 'asset',
          fileName: 'assets/theme.json',
          source: JSON.stringify(themeJson, null, 2)
        })
      } catch (error) {
        this.error(error.message)
      }
    },
  }
}
