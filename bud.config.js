/**
 * @typedef {import('@roots/bud').Bud} bud
 *
 * @param {bud} app
 */
module.exports = async (app) => {
  app
    /**
     * Application entrypoints
     *
     * Paths are relative to your resources directory
     */
    .entry({
      app: ['@scripts/app', '@styles/app'],
      editor: ['@scripts/editor', '@styles/editor'],
    })

    /**
     * These files should be processed as part of the build
     * even if they are not explicitly imported in application assets.
     */
    .assets('images')

    /**
     * These files will trigger a full page reload
     * when modified.
     */
    .watch(['{resources,app}**/*.php'])

    /**
     * Target URL to be proxied by the dev server.
     *
     * This is your local dev server.
     */
    .proxy('http://tinypixel.sabo')

    /**
     * Development URL
     */
    .serve('http://tinypixel.sabo:3000');
};
