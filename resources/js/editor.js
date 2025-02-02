import domReady from '@wordpress/dom-ready';

domReady(() => {
  // DOM has been loaded
});

if (import.meta.hot) {
  import.meta.hot.on('vite:beforeUpdate', (payload) => {
    const cssUpdates = payload.updates.filter(update => update.type === 'css-update');

    if (cssUpdates.length > 0) {
      const update = cssUpdates[0];

      // Find the iframe
      const editorIframe = document.querySelector('iframe[name="editor-canvas"]');
      if (!editorIframe?.contentDocument) {
        window.location.reload();
        return;
      }

      // Find the existing style tag in the iframe
      const styles = editorIframe.contentDocument.getElementsByTagName('style');
      let editorStyle = null;
      for (const style of styles) {
        if (style.textContent.includes('editor.css')) {
          editorStyle = style;
          break;
        }
      }

      if (!editorStyle) {
        window.location.reload();
        return;
      }

      // Update the style content with new import and cache-busting timestamp
      const timestamp = Date.now();
      editorStyle.textContent = `@import url('${window.__vite_client_url}${update.path}?t=${timestamp}')`;
      return;
    }

    // For non-CSS updates, reload
    window.location.reload();
  });
}
