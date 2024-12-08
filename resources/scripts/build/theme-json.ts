import type { Plugin } from 'vite'
import resolveConfig from 'tailwindcss/resolveConfig'

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

/**
 * Converts a slug or kebab-case string into Title Case.
 * 
 * Examples:
 * - Input: "primary-color" -> Output: "Primary Color"
 * - Input: "text-lg" -> Output: "Text Lg"
 */
const toTitleCase = (slug: string): string =>
  slug
    .split('-')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')

/**
 * Checks if a value is a valid CSS color.
 * 
 * Examples:
 * - Input: "#ff0000" -> true
 * - Input: "rgb(255, 255, 0)" -> true
 * - Input: "invalid-color" -> false
 */
const isValidColor = (value: any): boolean =>
  typeof value === 'string' && (value.startsWith('#') || value.startsWith('rgb'))

/**
 * Recursively processes a Tailwind color object into an array of ThemeJsonColor.
 */
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

// Convert Tailwind values to Theme JSON structures
const convertTailwindColorsToThemeJson = (config: any): ThemeJsonColor[] =>
  processColors(resolveConfig(config).theme.colors)

const convertTailwindFontFamiliesToThemeJson = (
  config: any
): ThemeJsonFontFamily[] =>
  Object.entries(resolveConfig(config).theme.fontFamily).map(([name, value]) => ({
    name: toTitleCase(name),
    slug: name.toLowerCase(),
    fontFamily: Array.isArray(value) ? value.join(', ') : value,
  }))

const convertTailwindFontSizesToThemeJson = (
  config: any
): ThemeJsonFontSize[] =>
  Object.entries(resolveConfig(config).theme.fontSize).map(([name, value]) => ({
    name: toTitleCase(name),
    slug: name.toLowerCase(),
    size: Array.isArray(value) ? value[0] : value,
  }))

// Merge default settings with user options
const mergeSettings = (
  defaults: ThemeJsonSettings,
  overrides: ThemeJsonSettings | undefined
): ThemeJsonSettings => ({ ...defaults, ...overrides })

// Plugin definition
export default function wordPressThemeJson(options: ThemeJsonOptions = {}): Plugin {
  const defaultSettings: ThemeJsonSettings = {
    background: { backgroundImage: true },
    color: {
      custom: false,
      customDuotone: false,
      customGradient: false,
      defaultDuotone: false,
      defaultGradients: false,
      defaultPalette: false,
      duotone: [],
    },
    custom: {
      spacing: {},
      typography: { 'font-size': {}, 'line-height': {} },
    },
    spacing: { padding: true, units: ['px', '%', 'em', 'rem', 'vw', 'vh'] },
    typography: { customFontSize: false },
  }

  const mergedSettings = mergeSettings(defaultSettings, options.settings)

  return {
    name: 'wordpress-theme-json',

    generateBundle() {
      const themeJson: Record<string, any> = {
        __generated__: '⚠️ This file is generated. Do not edit.',
        $schema: 'https://schemas.wp.org/trunk/theme.json',
        version: options.version ?? 3,
        settings: mergedSettings,
      }

      if (options.tailwindConfig) {
        const tailwindConfig = options.tailwindConfig

        if (!options.disableColors) {
          themeJson.settings.color = {
            ...(themeJson.settings.color || {}),
            palette: convertTailwindColorsToThemeJson(tailwindConfig),
          }
        }

        if (!options.disableFonts) {
          themeJson.settings.typography = {
            ...(themeJson.settings.typography || {}),
            fontFamilies: convertTailwindFontFamiliesToThemeJson(tailwindConfig),
          }
        }

        if (!options.disableFontSizes) {
          themeJson.settings.typography = {
            ...(themeJson.settings.typography || {}),
            fontSizes: convertTailwindFontSizesToThemeJson(tailwindConfig),
          }
        }
      }

      // Append optional fields
      if (options.customTemplates) themeJson.customTemplates = options.customTemplates
      if (options.patterns) themeJson.patterns = options.patterns
      if (options.styles) themeJson.styles = options.styles
      if (options.templateParts) themeJson.templateParts = options.templateParts
      if (options.title) themeJson.title = options.title

      this.emitFile({
        type: 'asset',
        fileName: options.fileName || 'theme.json',
        source: JSON.stringify(themeJson, null, 2),
      })
    },
  }
}