var path = require('path');
var autoprefixer = require('autoprefixer');

module.exports = {
  mode: 'production',
  entry: './markup/assets/es6/main.js',
  output: {
    path: path.resolve(__dirname, './wp-content/themes/cambiar-investors/assets/js/'),
    filename: 'main.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        options: {
          presets: ['@babel/preset-env']
        }
      },
      {
        test: /\.css$/,
        use: [
          {
            loader: 'style-loader'
          },
          {
            loader: 'css-loader'
          },
          {
            loader: 'postcss-loader',
            options: {
              plugins: [
                autoprefixer({
                  browsers:['last 4 version', 'Android 4']
                })
              ],
              sourceMap: true
            }
          }
        ]
      }
    ]
  },
  watch: false
};
