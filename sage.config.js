// @ts-check
const { bud } = require("@roots/bud");

bud
  .srcPath(bud.env.get("APP_SRC"))
  .publicPath(bud.env.get("APP_PUBLIC"))
  .storage(bud.env.get("APP_STORAGE"))
  .proxy({
    host: bud.env.get("APP_HOST"),
    port: bud.env.get("APP_PORT"),
  });

bud.use([
  require("@roots/bud-eslint"),
  require("@roots/bud-babel"),
  require("@roots/bud-sass"),
  require("@roots/bud-react"),
  require("@roots/bud-entrypoints"),
  require("@roots/bud-wordpress-externals"),
  require("@roots/bud-wordpress-manifests"),
]);

bud.alias({
  "@scripts": "scripts",
  "@styles": "styles",
  "@fonts": "fonts",
  "@images": "images",
});

bud.when(bud.isProduction, () =>
  bud
    .use([require("@roots/bud-terser")])
    .minify()
    .vendor()
    .runtime()
    .hash()
);

bud
  .entry("app", ["styles/app.scss", "scripts/app.js"])
  .entry("editor", ["styles/editor.scss", "scripts/editor.js"]);

bud.run();
