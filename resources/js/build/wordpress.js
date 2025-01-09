import resolveConfig from 'tailwindcss/resolveConfig'
import { defaultRequestToExternal, defaultRequestToHandle } from '@wordpress/dependency-extraction-webpack-plugin/lib/util'
import fs from 'fs'
import path from 'path'

/**
 * WordPress dependency extraction for Vite.
 *
 * This plugin configures Vite to exclude WordPress packages from your bundle
 * and instead load them from the `window.wp` global. It also generates a
 * dependency file that is read by Sage's setup.php to enqueue required
 * WordPress scripts.
 *
 * @returns {import('vite').Plugin}
 */

const externalMap = new Map()

export function extractWordPressDependencies() {
  return {
    name: 'wordpress-dependencies',
    config() {
      return {
        build: {
          rollupOptions: {
            external(id) {
              const result = defaultRequestToExternal(id)
              if (result) {
                externalMap.set(id, result)
                return true
              }
              return false
            },
            output: {
              globals(id) {
                const global = externalMap.get(id)
                return Array.isArray(global) ? global.join('.') : global
              }
            }
          }
        }
      }
    },
    generateBundle(options, bundle) {
      const deps = Object.values(bundle)
        .filter(chunk => chunk.type === 'chunk' && chunk.isEntry)
        .flatMap(chunk => chunk.imports)
        .map(defaultRequestToHandle)
        .filter(Boolean)

      if (deps.length) {
        this.emitFile({
          type: 'asset',
          fileName: 'editor.deps.json',
          source: JSON.stringify(deps, null, 2)
        })
      }
    }
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
export function processThemeJson({
  tailwindConfig,
  disableTailwindColors = false,
  disableTailwindFonts = false,
  disableTailwindFontSizes = false,
}) {
  function flattenColors(colors, prefix = '') {
    return Object.entries(colors).reduce((acc, [name, value]) => {
      const formattedName = name.charAt(0).toUpperCase() + name.slice(1)

      if (typeof value === 'string') {
        acc.push({
          name: prefix ? `${prefix.charAt(0).toUpperCase() + prefix.slice(1)}-${formattedName}` : formattedName,
          slug: prefix ? `${prefix}-${name}`.toLowerCase() : name.toLowerCase(),
          color: value,
        })
      } else if (typeof value === 'object') {
        acc.push(...flattenColors(value, name))
      }
      return acc
    }, [])
  }

  const resolvedConfig = resolveConfig(tailwindConfig)

  return {
    name: 'wordpress-theme-json',
    async generateBundle() {
      const baseThemeJson = JSON.parse(
        fs.readFileSync(path.resolve('./theme.json'), 'utf8')
      )

      const themeJson = {
        __processed__: "This file was generated from the Vite build",
        ...baseThemeJson,
        settings: {
          ...baseThemeJson.settings,
          ...((!disableTailwindColors && resolvedConfig.theme?.colors && {
            color: {
              ...baseThemeJson.settings?.color,
              palette: flattenColors(resolvedConfig.theme.colors),
            },
          }) || {}),
          ...((!disableTailwindFonts && resolvedConfig.theme?.fontFamily && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontFamilies: Object.entries(resolvedConfig.theme.fontFamily)
                .map(([name, value]) => ({
                  name,
                  slug: name,
                  fontFamily: Array.isArray(value) ? value.join(',') : value,
                })),
            },
          }) || {}),
          ...((!disableTailwindFontSizes && resolvedConfig.theme?.fontSize && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontSizes: Object.entries(resolvedConfig.theme.fontSize)
                .map(([name, value]) => ({
                  name,
                  slug: name,
                  size: Array.isArray(value) ? value[0] : value,
                })),
            },
          }) || {}),
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
