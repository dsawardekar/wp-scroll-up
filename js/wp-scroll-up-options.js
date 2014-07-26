(function($) {

  var getScrollUpOptions = function() {
    return wp_scroll_up.options;
  };

  $(document).ready(function() {
    $.scrollUp(getScrollUpOptions());
  });

}(jQuery));
