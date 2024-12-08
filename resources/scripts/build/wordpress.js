import {
    defaultRequestToExternal,
    defaultRequestToHandle,
  } from '@wordpress/dependency-extraction-webpack-plugin/lib/util'
  
  function extractNamedImports(imports) {
    return imports
      .match(/{([^}]+)}/)[1]
      .split(',')
      .map((s) => s.trim())
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
    if (imports.includes('{')) {
      const namedImports = extractNamedImports(imports)
  
      return handleNamedReplacement(namedImports, external)
    }
  
    if (imports.includes('* as')) {
      const alias = imports.match(/\*\s+as\s+(\w+)/)[1]
  
      return `const ${alias} = ${external.join('.')};`
    }
  
    const name = imports.trim()
  
    return `const ${name} = ${external.join('.')};`
  }
  
  function wordpressPlugin() {
    const dependencies = new Set()
  
    return {
      name: 'wordpress-plugin',
      enforce: 'post',
      transform(code, id) {
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
  
  function wordpressRollupPlugin() {
    return {
      name: 'wordpress-rollup-plugin',
      options(opts) {
        opts.external = (id) => id.startsWith('@wordpress/')
        opts.output = opts.output || {}
        opts.output.globals = (id) => {
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
  
  export { wordpressPlugin, wordpressRollupPlugin }