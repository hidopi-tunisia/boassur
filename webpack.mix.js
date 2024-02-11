const mix = require("laravel-mix");
require("laravel-mix-tailwind");
const tailwindcss = require("tailwindcss");

const confBack = tailwindcss("./tailwind.config.js");
const confFront = tailwindcss("./tailwind-front.config.js");

mix
  .js("resources/js/front/main.js", "public/js")
  .react()
  .postCss("resources/css/style.css", "public/css", [confFront]);

mix
  .js("resources/js/app.js", "public/js")
  .postCss("resources/css/app.css", "public/css", [confBack]);

if (mix.inProduction()) {
  mix.version();
}

mix.disableNotifications();
