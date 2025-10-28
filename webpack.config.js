const path = require('path');
const fs = require('fs');
const del = require('del');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const archiver = require('archiver');

// Obter versão do tema
const VERSION = getVersion();
console.log('version', VERSION);
const THEME_NAME = "bolt";
const CHANGELOG = getVersionChangelog();
const env = process.env.GCLOUD_ENV || '';
const CLOUD_FILES_PATH = process.env.CLOUD_FILES_PATH || `https://assets${env}.brius.com.br/themes/${THEME_NAME}/`;

// Definir a configuração base do Webpack
const baseConfig = {
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader'
        ]
      }
    ]
  },
  optimization: {
    minimizer: [
      new TerserPlugin({
        extractComments: false,
        terserOptions: {
          format: {
            comments: false,
          },
        },
      }),
      new CssMinimizerPlugin()
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].min.css'
    })
  ]
};

// Criar configurações para cada arquivo JS
const jsFiles = [
  'editor-functions',
  'editor-functions-gutenberg',
  'infinite-scroll',
  'lazy-load-img',
  'lazy-sections',
  'mobile-menu',
  'pre-render-pages',
  'paged',
  'post-publish-monitor',
  'reveal-comments',
  'theme-customizer'
];

// Configuração para CSS
const cssConfig = {
  ...baseConfig,
  name: 'style',
  entry: {
    'style-new': './src/sass/app.scss'
  },
  output: {
    path: path.resolve(__dirname, 'includes/assets/css'),
    filename: '[name].min.js' // Arquivo JS não será usado
  }
};

// Configuração para cada arquivo JS
const jsConfigs = jsFiles.map(file => ({
  ...baseConfig,
  name: file,
  entry: {
    [file]: `./src/js/${file}.js`
  },
  output: {
    path: path.resolve(__dirname, 'includes/assets/js'),
    filename: '[name].min.js'
  }
}));

// Funções auxiliares para outras partes do código
function getVersion() {
  const package = fs.readFileSync('./package.json', 'utf8');
  return JSON.parse(package).version;
}

function getVersionChangelog() {
  try {
    const file = fs.readFileSync('./CHANGELOG.md', 'utf8');
    const separatedVersions = file.split(/\[\d\.\d\.\d\]/);
    const currentVersionCL = separatedVersions[1];
    let replaced = currentVersionCL.replaceAll(/\(.*?\)/ig, "").replaceAll(')', '');
    
    replaced.match(/(### =?).*/g).forEach(piece => {
      const extracted = piece.replace('### ', '');
      replaced = replaced.replace(piece, `<h4>${extracted}</h4>`);
    });

    replaced.match(/(\* =?).*/g).forEach((piece) => {
      const extracted = piece.replace('* ', '');
      replaced = replaced.replace(piece, `<li>${extracted}</li>`);
    });

    replaced = replaced.replaceAll(/\r|\n/g, "");
    replaced = replaced.replaceAll('</h4><li>', '</h4><ul><li>');
    replaced = replaced.replaceAll('</li><h4>', '</li></ul><h4>');
    replaced = replaced.replaceAll('<h4></h4>', '');
    let releaseDate = file.match(/\(.{4}\-.{2}\-.{2}\)/)[0];
    releaseDate = releaseDate.replace(/(\(|\))/g, '');
    return `<img style="max-width: 100%;" src="bolt-2.jpg"/><h3>Version ${VERSION} - ${releaseDate}</h3>${replaced}`;
  } catch {
    return `<h3>Version ${VERSION}</h3>`;
  }
}

// Exportar todas as configurações
module.exports = [cssConfig, ...jsConfigs]; 