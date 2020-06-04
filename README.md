# Shopware Six (6) Developer Tool
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/marcofaul/sw-dev-tool-six-bundle)
[![Build Status](https://travis-ci.com/MarcoFaul/sw-dev-tool-six-bundle.svg?branch=master)](https://travis-ci.com/MarcoFaul/sw-dev-tool-six-bundle)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Coverage Status](https://coveralls.io/repos/github/MarcoFaul/sw-dev-tool-six-bundle/badge.svg?branch=master)](https://coveralls.io/github/MarcoFaul/sw-dev-tool-six-bundle?branch=master)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

## Features
- Set administration login token ttl (time to live)
- Disable dal entity caching
- Set default development parameters
- Disables twig cache
- Disable storefront error handling

## Installation

    composer require marcofaul/sw-dev-tool-six-bundle --dev

*This is optional.* 
Shopware 6 is using flex, so it will be automatically added to your `config/bundles.php`:

    return [
          // ...
          MarcoFaul\SwDevToolSixBundle\SwDevToolSixBundle::class => ['all' => true]  
          // ...
    ];

## Configuration
Simply configure your shop connection over the global `config.yml`:

    sw_dev_tool_six:
        access_token_ttl: P1W
        enable_dal_caching: false
        shopware:
            run_wizard: true
            auto_update: false
            api_auth_require: false
            storefront_csrf: false
        twig:
            debug: true

## Tests

    php vendor/bin/simple-phpunit
    or
    php vendor/bin/phpunit
    
