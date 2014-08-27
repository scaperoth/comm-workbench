$.stellar.scrollProperty.transform = {
  getLeft: function($element) {
    return parseInt($element.css('margin-left'), 10) * -1;
  },
  getTop: function($element) {
    return parseInt($element.css('margin-top'), 10) * -1;
  }
}