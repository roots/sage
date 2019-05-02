import editorConfig from './../../assets/editor.json'

wp.domReady(() => {
  editorConfig.blacklist.forEach(block => {
    wp.blocks.unregisterBlockType(block)
  })
})
