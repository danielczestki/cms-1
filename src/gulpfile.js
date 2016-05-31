var elixir = require("laravel-elixir");
require("laravel-elixir-stylus");

elixir(function(mix) {
   mix.stylus("app.styl");
});

elixir(function(mix) {
  mix.browserify("app.js");
});