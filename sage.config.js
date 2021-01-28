// @ts-check
const { sage } = require("@roots/sage");

sage
  .entry("app", ["styles/app.scss", "scripts/app.js"])
  .entry("editor", ["styles/editor.scss", "scripts/editor.js"])
  .run();
