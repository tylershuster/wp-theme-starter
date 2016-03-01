var webpack = require('webpack');
/**
 * Override the Default
 * Core Scripts
 * Config
 *
 */
module.exports = {
  options: {
    webpack: {
      defaults: {
        externals: {
          jquery: 'window.jQuery'
        }
      }
    }
  }
};
