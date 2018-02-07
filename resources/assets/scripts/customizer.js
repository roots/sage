wp.customize('blogname', (value) => {
  value.bind(to => querySelector('.brand').text(to));
});
