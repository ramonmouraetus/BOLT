const path = require('path');
const TerserPlugin = require("terser-webpack-plugin");
const fs = require('fs');

var minimizerConfig = {
    module: {},
    optimization: {
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    keep_fnames: false,
                    sourceMap: true
                },
            }),
        ],
    },
};

const assets = [];

const jsScriptsFolder = './includes/assets/js/';
const jsDistFolder = './includes/assets/js/'
const jsFiles = fs.readdirSync( jsScriptsFolder);
for ( const file of jsFiles ) {
    if ( file.includes('.min.js') ) continue;
    const minfileName = file.replace('.js', '.min.js');
    assets.push(
        Object.assign({}, minimizerConfig, {
            name: file,
            entry: `${jsScriptsFolder}${file}`,
            output: {
                path: path.resolve(__dirname, jsDistFolder),
                filename: minfileName
            }
        })
    );
}

module.exports = assets;