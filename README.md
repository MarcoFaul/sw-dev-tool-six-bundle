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

## Installation

    composer require marcofaul/sw-dev-tool-six-bundle --dev

In your `AppKernel`:

    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = [
          // ...
          new \JMS\SerializerBundle\JMSSerializerBundle(),
          new \MarcoFaul\SwDevToolSixBundle\SwDevToolSixBundle(),
        ];
         // ...
         
        return $bundles;
    }

## Configuration
Simply configure your shop connection over the global `config.yml`:

    sw_dev_tool_six:
        access_token_ttl: P1W
        enable_dal_caching: false
        shopware:
            skip_first_run_wizard_client: true
            enable_auto_update: false
            enable_api_auth_require: false
            enable_storefront_csrf: false

## Tests

    php vendor/bin/simple-phpunit
