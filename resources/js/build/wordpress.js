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
      if (!id.endsWith('.js')) return

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
    // Hook into Tailwind's transformed CSS
    transform(code, id) {
      if (id.endsWith('.css') && code.includes('@layer theme')) {
        cssContent = code
      }
      return null
    },

    async generateBundle() {
      if (!cssContent) {
        console.warn('No Tailwind CSS content found for theme.json generation')
        return
      }

      const baseThemeJson = JSON.parse(
        fs.readFileSync(path.resolve('./theme.json'), 'utf8')
      )

      // Extract variables from the theme layer
      const themeLayer = cssContent.match(/@layer theme\s*{[^}]*}/s)?.[0] || ''
      const rootVars = themeLayer.match(/:root\s*{([^}]*)}/s)?.[1] || ''

      const variables = {}
      const varRegex = /--([^:]+):\s*([^;]+);/g
      let match

      while ((match = varRegex.exec(rootVars)) !== null) {
        const [, name, value] = match
        variables[name] = value.trim()
      }

      // Process colors
      const colors = []
      Object.entries(variables).forEach(([name, value]) => {
        if (name.startsWith('color-') && !name.includes('--line-height')) {
          const [, colorName, shade] = name.match(/^color-([^-]+)-(\d+)$/) || []
          if (colorName && shade) {
            colors.push({
              name: `${colorName}-${shade}`,
              slug: `${colorName}-${shade}`.toLowerCase(),
              color: `var(--${name})`,
            })
          }
        }
      })

      // Process font families
      const fontFamilies = []
      Object.entries(variables).forEach(([name, value]) => {
        if (name.startsWith('font-') && !name.includes('-feature-settings') && !name.includes('-variation-settings')) {
          const fontName = name.replace('font-', '')
          fontFamilies.push({
            name: fontName,
            slug: fontName.toLowerCase(),
            fontFamily: value,
          })
        }
      })

      // Process font sizes
      const fontSizes = []
      Object.entries(variables).forEach(([name, value]) => {
        if (name.startsWith('text-') && !name.includes('--line-height')) {
          const sizeName = name.replace('text-', '')
          fontSizes.push({
            name: sizeName,
            slug: sizeName.toLowerCase(),
            size: value,
          })
        }
      })

      const themeJson = {
        __processed__: "This file was generated from Tailwind v4 CSS variables",
        ...baseThemeJson,
        settings: {
          ...baseThemeJson.settings,
          ...((!disableTailwindColors && {
            color: {
              ...baseThemeJson.settings?.color,
              palette: colors,
            },
          })),
          ...((!disableTailwindFonts && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontFamilies,
            },
          })),
          ...((!disableTailwindFontSizes && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontSizes,
            },
          })),
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
