import type { Plugin } from 'vite'
import resolveConfig from 'tailwindcss/resolveConfig'
import {
  defaultRequestToExternal,
  defaultRequestToHandle,
} from '@wordpress/dependency-extraction-webpack-plugin/lib/util'
import fs from 'fs'
import path from 'path'

// Theme JSON Types
interface ThemeJsonColor {
  name: string
  slug: string
  color: string
}

interface ThemeJsonFontFamily {
  name: string
  slug: string
  fontFamily: string
}

interface ThemeJsonFontSize {
  name: string
  slug: string
  size: string
}

interface ThemeJsonSettings {
  background?: {
    backgroundImage?: boolean
  }
  color?: {
    custom?: boolean
    customDuotone?: boolean
    customGradient?: boolean
    defaultDuotone?: boolean
    defaultGradients?: boolean
    defaultPalette?: boolean
    duotone?: any[]
    palette?: ThemeJsonColor[]
  }
  custom?: {
    spacing?: Record<string, any>
    typography?: {
      'font-size'?: Record<string, any>
      'line-height'?: Record<string, any>
    }
  }
  spacing?: {
    padding?: boolean
    units?: string[]
  }
  typography?: {
    customFontSize?: boolean
    dropCap?: boolean
    fontFamilies?: ThemeJsonFontFamily[]
    fontSizes?: ThemeJsonFontSize[]
  }
}

interface ThemeJsonOptions {
  tailwindConfig?: any
  settings?: ThemeJsonSettings
  fileName?: string
  version?: number
  disableColors?: boolean
  disableFonts?: boolean
  disableFontSizes?: boolean
  customTemplates?: Array<{
    name: string
    title: string
  }>
  patterns?: Array<{
    name: string
    title: string
    content: string
  }>
  styles?: Record<string, any>
  templateParts?: Array<{
    name: string
    title: string
    area: string
  }>
  title?: string
}

// WordPress Plugin Helper Functions
function extractNamedImports(imports: string): string[] {
  const match = imports.match(/{([^}]+)}/)
  if (!match) return []
  return match[1]
    .split(',')
    .map((s) => s.trim())
}

function handleNamedReplacement(namedImports: string[], external: string[]): string {
  return namedImports
    .map((imports) => {
      const [name, alias = name] = imports
        .split(' as ')
        .map((script) => script.trim())

      return `const ${alias} = ${external.join('.')}.${name};`
    })
    .join('\n')
}

function handleReplacements(imports: string[], external: string[]): string {
  if (typeof imports === 'string') {
    imports = [imports]
  }

  if (imports[0].includes('{')) {
    const namedImports = extractNamedImports(imports[0])
    return handleNamedReplacement(namedImports, external)
  }

  if (imports[0].includes('* as')) {
    const match = imports[0].match(/\*\s+as\s+(\w+)/)
    if (!match) return ''
    const alias = match[1]
    return `const ${alias} = ${external.join('.')};`
  }

  const name = imports[0].trim()
  return `const ${name} = ${external.join('.')};`
}

// Theme JSON Helper Functions
const toTitleCase = (slug: string): string =>
  slug
    .split('-')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')

const isValidColor = (value: any): boolean =>
  typeof value === 'string' && (value.startsWith('#') || value.startsWith('rgb'))

const processColors = (
  obj: Record<string, any>,
  prefix = ''
): ThemeJsonColor[] => {
  const palette: ThemeJsonColor[] = []

  for (const [key, value] of Object.entries(obj)) {
    const name = prefix ? `${prefix} ${key}` : key
    const slug = name.toLowerCase().replace(/\s+/g, '-')

    if (isValidColor(value)) {
      palette.push({ name: toTitleCase(name), slug, color: value })
      continue
    }

    if (value && typeof value === 'object' && !Array.isArray(value)) {
      const nestedColors = processColors(value, name)
      palette.push(...nestedColors)
    }
  }

  return palette
}

// Conversion Functions
const convertTailwindColorsToThemeJson = (config: any): ThemeJsonColor[] =>
  processColors(resolveConfig(config).theme.colors)

const convertTailwindFontFamiliesToThemeJson = (
  config: any
): ThemeJsonFontFamily[] =>
  Object.entries(resolveConfig(config).theme.fontFamily).map(([name, value]) => ({
    name: toTitleCase(name),
    slug: name.toLowerCase(),
    fontFamily: Array.isArray(value) ? value.join(', ') : String(value),
  }))

const convertTailwindFontSizesToThemeJson = (
  config: any
): ThemeJsonFontSize[] =>
  Object.entries(resolveConfig(config).theme.fontSize).map(([name, value]) => ({
    name: toTitleCase(name),
    slug: name.toLowerCase(),
    size: Array.isArray(value) ? value[0] : value,
  }))

const mergeSettings = (
  defaults: ThemeJsonSettings,
  overrides: ThemeJsonSettings | undefined
): ThemeJsonSettings => ({ ...defaults, ...overrides })

// Plugin Exports
export function wordpressPlugin(): Plugin {
  const dependencies = new Set<string>()

  return {
    name: 'wordpress-plugin',
    enforce: 'post',
    transform(code: string, id: string) {
      if (!id.endsWith('.js')) {
        return
      }

      const imports = [
        ...(code.match(/^import .+ from ['"]@wordpress\/[^'"]+['"]/gm) || []),
        ...(code.match(/^import ['"]@wordpress\/[^'"]+['"]/gm) || []),
      ]

      imports.forEach((statement) => {
        const match =
          statement.match(/^import (.+) from ['"]@wordpress\/([^'"]+)['"]/) ||
          statement.match(/^import ['"]@wordpress\/([^'"]+)['"]/)

        if (!match) {
          return
        }

        const [, imports, pkg] = match

        if (!pkg) {
          return
        }

        const external = defaultRequestToExternal(`@wordpress/${pkg}`)
        const handle = defaultRequestToHandle(`@wordpress/${pkg}`)

        if (external && handle) {
          dependencies.add(handle)

          const replacement = imports
            ? handleReplacements(imports, external)
            : `const ${pkg.replace(/-([a-z])/g, (_, letter) =>
                letter.toUpperCase()
              )} = ${external.join('.')};`

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

export function wordpressRollupPlugin(): Plugin {
  return {
    name: 'wordpress-rollup-plugin',
    options(opts: any) {
      opts.external = (id: string) => id.startsWith('@wordpress/')
      opts.output = opts.output || {}
      opts.output.globals = (id: string) => {
        if (id.startsWith('@wordpress/')) {
          const packageName = id.replace('@wordpress/', '')
          return `wp.${packageName.replace(/-([a-z])/g, (_, letter) =>
            letter.toUpperCase()
          )}`
        }
      }
      return opts
    },
  }
}

function flattenColors(colors: Record<string, any>, prefix = '') {
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
  }, [] as Array<{ name: string; slug: string; color: string }>)
}

export function wordpressThemeJson({
  tailwindConfig,
  disableColors = false,
  disableFonts = false,
  disableFontSizes = false,
}) {
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
          ...((!disableColors && resolvedConfig.theme?.colors && {
            color: {
              ...baseThemeJson.settings?.color,
              palette: flattenColors(resolvedConfig.theme.colors),
            },
          }) ||
            {}),
          ...((!disableFonts && resolvedConfig.theme?.fontFamily && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontFamilies: Object.entries(resolvedConfig.theme.fontFamily)
                .map(([name, value]) => ({
                  name,
                  slug: name,
                  fontFamily: Array.isArray(value) ? value.join(',') : value,
                })),
            },
          }) ||
            {}),
          ...((!disableFontSizes && resolvedConfig.theme?.fontSize && {
            typography: {
              ...baseThemeJson.settings?.typography,
              fontSizes: Object.entries(resolvedConfig.theme.fontSize)
                .map(([name, value]) => ({
                  name,
                  slug: name,
                  size: Array.isArray(value) ? value[0] : value,
                })),
            },
          }) ||
            {}),
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
