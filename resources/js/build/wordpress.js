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
    return match[1].split(',').map((s) => s.trim())
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
      if ((!id.endsWith('.js')) && !id.endsWith('.jsx')) return

      const imports = [
        ...(code.match(/^import .+ from ['"]@wordpress\/[^'"]+['"]/gm) || []),
        ...(code.match(/^import ['"]@wordpress\/[^'"]+['"]/gm) || []),
      ]

      imports.forEach((statement) => {
        const match =
          statement.match(/^import (.+) from ['"]@wordpress\/([^'"]+)['"]/) ||
          statement.match(/^import ['"]@wordpress\/([^'"]+)['"]/)

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
 * Generates a WordPress theme.json file by combining:
 * - Base theme.json settings
 * - Tailwind configuration (colors, fonts, font sizes)
 *
 * The generated theme.json is emitted to public/build/assets/theme.json
 * and provides WordPress with theme settings that match your Tailwind configuration.
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
        return;
      }

      const baseThemeJson = JSON.parse(
        fs.readFileSync(path.resolve('./theme.json'), 'utf8')
      )

      const themeContent = (() => {
        const match = cssContent.match(/@(?:layer\s+)?theme\s*{/s)
        
        if (!match[0]) {
          return null
        }
        const startIndex = match.index + match[0].length;
        let braceCount = 1;
        for (let i = startIndex; i < cssContent.length; i++) { 
          if (cssContent[i] === "{") braceCount++;
          if (cssContent[i] === "}") braceCount--;
          if (braceCount === 0) {
            return cssContent.substring(startIndex, i );
          }
        }
        return null
      })()
      
      if (!themeContent) {
        return;
      }
      
      if (!themeContent.trim().startsWith(':root')) {
        return;
      }

      const rootContent = themeContent.slice(themeContent.indexOf('{') + 1, themeContent.lastIndexOf('}'))
      const colorVariables = {}

      const colorVarRegex = /--color-([^:]+):\s*([^;}]+)[;}]?/g
      let match

      while ((match = colorVarRegex.exec(rootContent)) !== null) {
        const [, name, value] = match
        colorVariables[name] = value.trim()
      }

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

      const fontFamilies = []
      const fontVarRegex = /--font-([^:]+):\s*([^;}]+)[;}]?/g
      while ((match = fontVarRegex.exec(rootContent)) !== null) {
        const [, name, value] = match
        if (!name.includes('-feature-settings') && !name.includes('-variation-settings')) {
          fontFamilies.push({
            name: name,
            slug: name.toLowerCase(),
            fontFamily: value.trim(),
          })
        }
      }

      const fontSizes = []
      const fontSizeVarRegex = /--text-([^:]+):\s*([^;}]+)[;}]?/g
      while ((match = fontSizeVarRegex.exec(rootContent)) !== null) {
        const [, name, value] = match
        if (!name.includes('--line-height')) {
          fontSizes.push({
            name: name,
            slug: name.toLowerCase(),
            size: value.trim(),
          })
        }
      }

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
          ...((!disableTailwindFonts && fontFamilies.length > 0) && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontFamilies,
            },
          }),
          ...((!disableTailwindFontSizes && fontSizes.length > 0) && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontSizes,
            },
          }),
        },
      }

      delete themeJson.__preprocessed__

      this.emitFile({
        type: 'asset',
        fileName: 'assets/theme.json',
        source: JSON.stringify(themeJson, null, 2)
      })
    },
  }
}
