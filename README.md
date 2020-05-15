# Shopware Developer Tool
=======================

## Todos: 
- [ ] Fix config
- [ ] Set description to config + readme config
- [ ] Set config aliax (devTool)
- [ ] Change readme
- [ ] write tests
- [ ] add other use full stuff


[![Build Status](https://travis-ci.com/MarcoFaul/sw-dev-tool-six-bundle.svg?branch=master)](https://travis-ci.com/MarcoFaul/sw-dev-tool-six-bundle)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![codecov](https://codecov.io/gh/MarcoFaul/sw-dev-tool-six-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/MarcoFaul/sw-dev-tool-six-bundle)

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
            skipFirstRunWizardClient: true
            disableAutoUpdate: true
            disableApiAuthRequire: true
            disableCSRF: true

## Tests

    php vendor/bin/simple-phpunit
