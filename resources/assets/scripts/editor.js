const blacklist = [
  'core/archives',
  'core/categories',
  'core/latest-comments',
  'core/latest-posts',
  'core/verse',
  'core-embed/animoto',
  'core-embed/cloudup',
  'core-embed/collegehumor',
  'core-embed/crowdsignal',
  'core-embed/dailymotion',
  'core-embed/funnyordie',
  'core-embed/hulu',
  'core-embed/issuu',
  'core-embed/kickstarter',
  'core-embed/meetup-com',
  'core-embed/mixcloud',
  'core-embed/photobucket',
  'core-embed/polldaddy',
  'core-embed/reverbnation',
  'core-embed/screencast',
  'core-embed/scribd',
  'core-embed/slideshare',
  'core-embed/smugmug',
  'core-embed/speaker-deck',
  'core-embed/ted',
  'core-embed/tumblr',
  'core-embed/videopress',
  'core-embed/wordpress-tv',
];

wp.domReady(() => {
  blacklist.forEach((block) => {
    wp.blocks.unregisterBlockType(block)
  })
})
