# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

### [3.0.0] (2024-06-30)

### Features

* Added MultiLanguage Feature;
* Added Translation with OpenAI native integration;
* Improved Semantic SEO;
* Improved SEO meta tags without specialized plugins;

### [2.0.4] (2024-10-23)

### Features

* Reduce mobile menu transition to improve INP page speed metric;

### [2.0.3] (2024-08-07)

### Features

* Force script async only at single pages;
* Add Image to meta author;
* Fix some margins;

### [2.0.1] (2024-08-06)

### Features

* Released version 2.0
* webp native optimization, giving up to 80% of file size compression
* aggregation of linked external css in HTML output reducing rendering block requests in page load
* force linked script tags to async (except JQuery). To skip this optimization use class="bolt-no-optimize"
* new layout and style for single post
* new typography
* added new colors settings for footer in mobile devices
* improved Core Web Vitals LCP metric

### [0.1.4](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.1.3...v0.1.4) (2023-11-28)


### Bug Fixes

* zip generation ([51a17d5](https://gitlab.com/etus/brius/wordpress/bolt/commit/51a17d539d1e21a714279fac7511fbed6bc0ab60))

### [0.1.3](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.1.2...v0.1.3) (2023-11-28)

### [0.1.2](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.1.1...v0.1.2) (2023-11-28)


### Bug Fixes

* acf folder ([65b7741](https://gitlab.com/etus/brius/wordpress/bolt/commit/65b77413c18eb7e5c9a2eae1d3df75cf0830cba7))

### [0.1.1](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.12...v0.1.1) (2023-11-28)


### Features

* add new author section option ([c43c5da](https://gitlab.com/etus/brius/wordpress/bolt/commit/c43c5da9986d5992618d90caac1a2355d6799e67))
* add user meta data ([603115a](https://gitlab.com/etus/brius/wordpress/bolt/commit/603115a0dd36f4fa92812a44a65433dabcee2700))
* new author meta layout ([9ee4969](https://gitlab.com/etus/brius/wordpress/bolt/commit/9ee49690e592ea6e66dfe6f263d02d6cd268224e))
* start with acf ([900a6e9](https://gitlab.com/etus/brius/wordpress/bolt/commit/900a6e957dfe431679e1e5a820dadc63a36813b8))
* v1 of rectangle author meta ([997da3b](https://gitlab.com/etus/brius/wordpress/bolt/commit/997da3bdd3c724816ae9f7b89e7d492f138d6bdc))


### Bug Fixes

* reset post data after querying in related posts ([cd0da34](https://gitlab.com/etus/brius/wordpress/bolt/commit/cd0da34febb59bcf11d3ff897460562fffb2191b))
* search form ([32e8490](https://gitlab.com/etus/brius/wordpress/bolt/commit/32e849069fe3a589074a0d32120d3a82cd1fd93a))

### [0.0.12](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.11...v0.0.12) (2023-05-23)


### Features

* remove all container margins from single ([4f1a61f](https://gitlab.com/etus/brius/wordpress/bolt/commit/4f1a61f1ce60e9d4e15d4e5be71a4f39633a7b08))
* remove all container margins from single only ([33efedf](https://gitlab.com/etus/brius/wordpress/bolt/commit/33efedffe2805c6f68514b696370eaeb66010e0c))


### Bug Fixes

* broken listeners when not running bolt templates ([961d028](https://gitlab.com/etus/brius/wordpress/bolt/commit/961d0281b968ffcce465403a65fcacfcf899f871))

### [0.0.11](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.10...v0.0.11) (2023-04-17)


### Bug Fixes

* default ad posision in top ad management CLS prevention ([a8ccf90](https://gitlab.com/etus/brius/wordpress/bolt/commit/a8ccf90c6f156a71c4f2b6d17e819b694c0a16ed))

### [0.0.10](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.9...v0.0.10) (2023-04-13)


### Bug Fixes

* removing search params when redirecting ([8c85bbf](https://gitlab.com/etus/brius/wordpress/bolt/commit/8c85bbf5324a08cb082536f6af51bc66eac82efe))

### [0.0.9](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.8...v0.0.9) (2023-02-14)


### Features

* establish stable version of top ad management ([5e812f2](https://gitlab.com/etus/brius/wordpress/bolt/commit/5e812f271490178e0c29fdbfdf080a0bcb991050))
* improve performance ([9e99039](https://gitlab.com/etus/brius/wordpress/bolt/commit/9e9903957734cc3ce681c56828a8b4fe68c12164))
* top ad features to reduce CLS ([4d443e0](https://gitlab.com/etus/brius/wordpress/bolt/commit/4d443e0fa754cf4926e3e6e1e66efccebb5a58a6))


### Bug Fixes

* php tags ([2ff8bdd](https://gitlab.com/etus/brius/wordpress/bolt/commit/2ff8bdd7dbb63c98464ae898acd6b7dcf360f770))
* ul ol auto list style ([6d6b58e](https://gitlab.com/etus/brius/wordpress/bolt/commit/6d6b58eef64d8192ab03a47ca598395c9dd6714a))

### [0.0.8](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.7...v0.0.8) (2023-02-07)


### Features

* add div with min-height to remove CLS ([df24891](https://gitlab.com/etus/brius/wordpress/bolt/commit/df24891007815373f6cc70d7c94085da5d8ca4a7))
* await event from load spinner plugin ([3344d2b](https://gitlab.com/etus/brius/wordpress/bolt/commit/3344d2bb31e311b539c5f8a5456ac7ab4cc591ce))
* enqueue default Jquery to improve performance ([5e06495](https://gitlab.com/etus/brius/wordpress/bolt/commit/5e06495615b49a70cb01911433ffdcc736065e3b))
* improve performance defering image calls ([201c99d](https://gitlab.com/etus/brius/wordpress/bolt/commit/201c99dadc16274dc7ce3c30b39e72f7fadc181f))


### Bug Fixes

* logo width and height sizes to improve page speed metrics ([8020a97](https://gitlab.com/etus/brius/wordpress/bolt/commit/8020a973ff566edd9759f39f4a2996b495c5d147))

### [0.0.7](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.6...v0.0.7) (2022-05-26)


### Bug Fixes

* mysql use forcing ([e15d54f](https://gitlab.com/etus/brius/wordpress/bolt/commit/e15d54fdb57693673b0e1d24b13daf7ff51338ac))

### [0.0.6](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.5...v0.0.6) (2022-05-25)


### Bug Fixes

* incompatibility with some brius plugins ([affa4da](https://gitlab.com/etus/brius/wordpress/bolt/commit/affa4da5d6b43af6e8cdb783a31ab0f963202d70))

### [0.0.5](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.4...v0.0.5) (2022-05-24)

### [0.0.4](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.3...v0.0.4) (2022-05-24)

### [0.0.3](https://gitlab.com/etus/brius/wordpress/bolt/compare/v0.0.2...v0.0.3) (2022-05-20)


### Features

* allow update check and install ([5c92782](https://gitlab.com/etus/brius/wordpress/bolt/commit/5c927820de52ccbe8b0462d4c92fb300f6dae538))
* improve performance and change style ([072938c](https://gitlab.com/etus/brius/wordpress/bolt/commit/072938c08550b728a0c8d156688d0cc95a11f55f))
* improve performance and change style ([5ab2997](https://gitlab.com/etus/brius/wordpress/bolt/commit/5ab29975663e6a124556331ff2eeb98df400de60))


### Bug Fixes

* ul and ol margin top ([00ede48](https://gitlab.com/etus/brius/wordpress/bolt/commit/00ede4861846d4d5c4db81e35bede092f0b3fe38))
