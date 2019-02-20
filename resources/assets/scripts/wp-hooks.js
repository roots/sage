wp.hooks.addFilter(
  'blocks.getSaveElement',
  'alignments/addWideWrap',
  addWideWrap
);

wp.hooks.addFilter(
  'blocks.getSaveElement',
  'alignments/addFullWrap',
  addFullWrap
);

function addWideWrap (element, blockType, attributes) {
  if (attributes.align !== 'wide') {
    return element
  }
  return wp.element.createElement(
    'div',
    { className: 'wp-block-wrap wp-block-wide-wrap' },
    element
  );
}

function addFullWrap (element, blockType, attributes) {
  if (attributes.align !== 'full') {
    return element
  }

  return wp.element.createElement(
    'div',
    { className: 'wp-block-wrap wp-block-full-wrap' },
    element
  );
}