var Config = function(configKey) {
  this.configKey = configKey;
  this.load();
};

Config.prototype = {

  load: function() {
    this.configObj = window[this.configKey];
  },

  getOptions: function(name) {
    return this.configObj.options;
  },

  getStyleTypes: function() {
    return this.configObj.styleTypes;
  },

  getAnimationTypes: function() {
    return this.configObj.animationTypes;
  },

  translate: function(name) {
    if (this.params.hasOwnProperty(name)) {
      return this.params[name];
    } else {
      return name;
    }
  }

};


module.exports = Config;
