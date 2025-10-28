# BOLT

## Getting Started
This project is a [wordpress theme](https://codex.wordpress.org/Theme_Development) optimized for high speed and advertising performance.
### Development
You can develop clonning the project in `wp-content/themes/` folder in your wordpress local environment. After that go to WP Panel >Appearance > Themes and activate the theme.
#### Conventional commits
This project was created following the [conventional commits](https://www.conventionalcommits.org/) pattern. So this pattern should be used accross all the development to keep our commits clean and more easy to comprehend. Non-standard commit attempts will be automatically rejected by the [@commitlint/config-conventional](https://www.npmjs.com/package/@commitlint/config-conventional) triggered by [husky](https://www.npmjs.com/package/husky).
#### Versioning control
This project uses [standard version](https://www.npmjs.com/package/standard-version) to help us in versioning control. Basically running `npm run release` or `yarn release` a incremental version of our project will be created, including a git tag and the CHANGELOG.md file will be updated. Following [conventional commits](https://www.conventionalcommits.org/) pattern, all commits of types `fix` and `feat` will be added to the CHANGELOG.md and this information will be used to show changelog of new versions in Wordpress themes panel.
The incremental logic is based in [semantic versioning](https://semver.org/):

 - 1 feat is one minor change (X.1.X);
 - 1 fix is one patch (X.X.1);
 - 1 BREAKING CHANGE is a major (1.X.X);
 - Only a number is increased. 3 feats will increase 1;
 - The bigger number is important. If has a feat, fix will be ignored;

### Exporting plugin
First of all, install the node.js dependencies:

`npm install` or `yarn install`

After desired changes run:

`npm run build` or `yarn build` to export the .zip file of plugin that can be uploaded to wordpress
