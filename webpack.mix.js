const mix = require('laravel-mix');
const del = require("del");
const fs = require('fs-extra');
const path = require('path');
require('laravel-mix-zip');
mix.options({ processCssUrls: false });

const VERSION = getVersion();
const THEME_NAME = "bolt";
const CHANGELOG = getVersionChangelog();
const env = process.env.GCLOUD_ENV || '';
const CLOUD_FILES_PATH = process.env.CLOUD_FILES_PATH || `https://assets${env}.brius.com.br/themes/${THEME_NAME}/`;
const EXCLUDE = ['.git', 'node_modules', 'vue-config'];


minifyAll();

if (mix.inProduction()) {
    copyFolders();
    generateZip();
}

function minifyAll() {
    mix.sass( 'src/sass/app.scss', 'includes/assets/css/style-new.min.css' )
        .sass( 'src/sass/breadcrumbs.scss', 'includes/assets/css/breadcrumbs.min.css' )
        .js( 'src/js/editor-functions.js', 'includes/assets/js/editor-functions.min.js' )
        .js( 'src/js/infinite-scroll.js', 'includes/assets/js/infinite-scroll.min.js' )
        .js( 'src/js/lazy-load-img.js', 'includes/assets/js/lazy-load-img.min.js' )
        .js( 'src/js/lazy-sections.js', 'includes/assets/js/lazy-sections.min.js' )
        .js( 'src/js/mobile-menu.js', 'includes/assets/js/mobile-menu.min.js' )
        .js( 'src/js/pre-render-pages.js', 'includes/assets/js/pre-render-pages.min.js' )
        .js( 'src/js/paged.js', 'includes/assets/js/paged.min.js' )
        .js( 'src/js/post-publish-monitor.js', 'includes/assets/js/post-publish-monitor.min.js' )
        .js( 'src/js/reveal-comments.js', 'includes/assets/js/reveal-comments.min.js' )
        .js( 'src/js/theme-customizer.js', 'includes/assets/js/theme-customizer.min.js' )
}


function shouldCopy(src, dest) {
    const base = src.split(path.sep).pop();
    return !EXCLUDE.includes(base);
}

async function copyFolders() {
    const myFolders = ['vendor', 'includes'];
    for (const folder of myFolders) {
        await fs.copy(`./${folder}`, `${THEME_NAME}/${folder}`, {
            filter: (src, dest) => shouldCopy(src, dest),
        });
    }
}

function generateZip( env ) {
    env = !!env ? `-${env}` : '';
    generateThemeInfo( env );
    mix.copy(`src/resources/*`, `dist/`);
    mix //.copy(["includes/"], `${THEME_NAME}/includes`)
        .copy(`*.php`, `${THEME_NAME}/`)
        //.copy('src/resources/*.', 'dist/')
        .copy('screenshot.png', `${THEME_NAME}/`)
        .zip(
            [`${THEME_NAME}/`],
            [],
            `dist/${THEME_NAME}${VERSION}${env}.zip`
        )
        .then(() => {
            createChangelogFile();
            fs.createReadStream('./style.css').pipe(fs.createWriteStream(`${THEME_NAME}/style.css`));
            //remove unused generated files/folder
            setTimeout(() => {
                del( `${THEME_NAME}/` );
            }, 5000);
        });

}

function createChangelogFile() {
    fs.writeFileSync( 'dist/changelog.html',  CHANGELOG );
}

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
        
        replaced.match( /(### =?).*/g ).forEach(piece => {
            const extracted = piece.replace( '### ', '' );
            replaced = replaced.replace( piece, `<h4>${extracted}</h4>` );
        });

        replaced.match( /(\* =?).*/g ).forEach( (piece) => {
            const extracted = piece.replace( '* ', '' );
            replaced = replaced.replace( piece, `<li>${extracted}</li>` );
        });

        replaced = replaced.replaceAll( /\r|\n/g, "" );
        replaced = replaced.replaceAll( '</h4><li>', '</h4><ul><li>' );
        replaced = replaced.replaceAll( '</li><h4>', '</li></ul><h4>' );
        replaced = replaced.replaceAll( '<h4></h4>', '' );
        let releaseDate = file.match(/\(.{4}\-.{2}\-.{2}\)/)[0];
        releaseDate = releaseDate.replace( /(\(|\))/g, '' );
        return `<img style="max-width: 100%;" src="bolt-2.jpg"/><h3>Version ${VERSION} - ${releaseDate}</h3>${replaced}`;
    } catch {
        return `<h3>Version ${VERSION}</h3>`;
    }
}

function changeVersionInFiles(fileNames = []) {
    for ( const fileName of fileNames ) {
        const themeMainFile = fs.readFileSync( fileName,'utf8' );
        console.log('its version =>', VERSION)
        fs.writeFileSync( fileName,  themeMainFile.replace( /\b\d+\.\d+\.\d+\b/g, VERSION ) );
    }
}

function generateThemeInfo( env ) {
    changeVersionInFiles([`./style.css`, './includes/theme-functions/core/globals.php']);
    const file = fs.readFileSync('./src/theme-info.example.json', 'utf8');
    const infoData = JSON.parse(file);
    const newInfo = {
        new_version: VERSION,
        package: `${CLOUD_FILES_PATH}${THEME_NAME}${VERSION}.zip`,
        url: `${CLOUD_FILES_PATH}changelog.html`,
        last_updated: new Date().toISOString(),
        sections: {
            description: "This plugin enable Brius for Publishers (BFP) to show Ads in Wordpress.",
            installation: "Click the activate/install button and that's it."
        },
        icons: {
            "2x": "https://assets.brius.com.br/themes/bolt/bolt-icon.png",
            "1x": "https://assets.brius.com.br/themes/bolt/bolt-icon-2.png"
        },
        banners: {
            low: "https://assets.brius.com.br/themes/bolt/bolt.jpg",
            high: "https://assets.brius.com.br/themes/bolt/bolt-2.jpg"
        }
    };
    const mergedInfo = {
        ...infoData,
        ...newInfo
    };
    const filename = `${THEME_NAME}-info.json`;
    if (!fs.existsSync('dist/')) fs.mkdirSync('dist');
    fs.writeFileSync( `dist/${filename}`, JSON.stringify( mergedInfo, null, 4 ) );
    const updateFile = fs.readFileSync('./includes/theme-configs/data/brius_theme_update_options.json', 'utf8');
    const updateInfo = JSON.parse(updateFile);
    updateInfo.bolt_update_url = `${CLOUD_FILES_PATH}${filename}`;
    fs.writeFileSync( './includes/theme-configs/data/brius_theme_update_options.json', JSON.stringify( updateInfo, null, 4 ) );
}