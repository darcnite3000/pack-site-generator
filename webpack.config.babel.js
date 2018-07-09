const {resolve} = require('path')
const webpack = require('webpack')
const ProgressBarPlugin = require('progress-bar-webpack-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const InlineManifestWebpackPlugin = require('inline-manifest-webpack-plugin')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const webpackValidator = require('webpack-validator')
const {getIfUtils, removeEmpty} = require('webpack-config-utils')
const autoprefixer = require('autoprefixer')

module.exports = env => {
  const {ifProd, ifNotProd} = getIfUtils(env)
  const config = webpackValidator({
    context: resolve('app'),
    resolve: {
      extensions: ['', '.js', '.jsx']
    },
    entry: {
      app: './BrowserBootstrap.jsx',
      vendor: []
    },
    output: {
      filename: ifProd('bundle.[name].[chunkhash].js', 'bundle.[name].js'),
      path: resolve('www'),
      pathinfo: ifNotProd()
    },
    devtool: ifProd('source-map', 'eval'),
    module: {
      loaders: [
        {
          test: /\.jsx?$/,
          loader: 'babel',
          exclude: /node_modules/
        },
        {
          test: /\.eot(\?v=\d+.\d+.\d+)?$/,
          loader: 'file'
        },
        {
          test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
          loader: 'url',
          query: {
            limit: 10000,
            mimetype: 'application/font-woff'
          }
        },
        {
          test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
          loader: 'url',
          query: {
            limit: 10000,
            mimetype: 'application/octet-stream'
          }
        },
        {
          test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
          loader: 'url',
          query: {
            limit: 10000,
            mimetype: 'image/svg+xml'
          }
        },
        {
          test: /\.(jpe?g|png|gif)$/i,
          loader: 'file',
          query: {
            name: '[name].[ext]'
          }
        },
        {
          test: /\.ico$/,
          loader: 'file',
          query: {
            name: '[name].[ext]'
          }
        },
        {
          test: /(\.css|\.scss)$/,
          loader: ExtractTextPlugin.extract({
            fallbackLoader: 'style',
            loader: ['css', 'postcss', 'sass']
          })
        }
      ]
    },
    plugins: removeEmpty([
      new ProgressBarPlugin(),
      new ExtractTextPlugin(
        ifProd('styles.[name].[chunkhash].css', 'styles.[name].css')
      ),
      ifProd(new InlineManifestWebpackPlugin()),
      ifProd(new webpack.optimize.CommonsChunkPlugin({
        names: ['vendor', 'manifest']
      })),
      new HtmlWebpackPlugin({
        template: './index.ejs',
        inject: 'head'
      }),
      new webpack.DefinePlugin({
        'process.env': {
          NODE_ENV: ifProd('"production"', '"development"')
        }
      })
    ]),
    postcss: () => [autoprefixer]
  })
  if (env.debug) {
    console.log(config)
    debugger // eslint-disable-line
  }
  return config
}
