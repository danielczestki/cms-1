var elixir = require("laravel-elixir");
require("laravel-elixir-stylus");

elixir(function(mix) {
   mix.stylus("app.styl");
});

/*elixir(function(mix) {
  mix.browserify('app.js');
});

elixir(function(mix) {
   mix.version(['css/app.css', 'js/app.js']);
});*/