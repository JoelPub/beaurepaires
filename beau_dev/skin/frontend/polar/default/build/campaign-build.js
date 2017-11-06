// https://github.com/shelljs/shelljs
require('./check-versions')()
process.env.name = 'bmc55';
console.log('Staring up campaign build');

// Checking that campaign name is set
if (process.env.name === undefined) {
  console.log('Campaign name not set!');
  console.log('You must run this command with param "name" to target the correct campaign folder.');
  console.log('EG: name=mycampaign npm run campaign-dev');
  process.exit()
} else if (process.env.name === 'mycampaign') {
  console.log('No, don\'t just set the name to "mycampaign"! Use the name of th ecampaign folder!');
  process.exit()
}

process.env.NODE_ENV = 'production'

var ora = require('ora')
var path = require('path')
var chalk = require('chalk')
var shell = require('shelljs')
var webpack = require('webpack')
var config = require('../config')
var webpackConfig = require('./webpack.campaign.prod.conf')

var spinner = ora('building for production...')
spinner.start()

var assetsPath = path.join(config.build.assetsRoot, config.build.assetsSubDirectory)
shell.rm('-rf', assetsPath)
shell.mkdir('-p', assetsPath)
shell.config.silent = true
shell.cp('-R', 'static/*', assetsPath)
shell.config.silent = false

webpack(webpackConfig, function (err, stats) {
  spinner.stop()
  if (err) throw err
  process.stdout.write(stats.toString({
    colors: true,
    modules: false,
    children: false,
    chunks: false,
    chunkModules: false
  }) + '\n\n')

  console.log(chalk.cyan('  Build complete.\n'))
  console.log(chalk.yellow(
    '  Tip: built files are meant to be served over an HTTP server.\n' +
    '  Opening index.html over file:// won\'t work.\n'
  ))
})
