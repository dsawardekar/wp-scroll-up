(function($) {

  var toInteger = function(key, options) {
    if (options.hasOwnProperty(key)) {
      options[key] = parseInt(options[key], 10);
    }
  };

  var getScrollUpOptions = function() {
    var options = jquery_scroll_up_options;
    toInteger('scrollDistance', options);
    toInteger('scrollSpeed', options);

    return options;
  };

  $(document).ready(function() {
    $.scrollUp(getScrollUpOptions());
  });

}(jQuery));
