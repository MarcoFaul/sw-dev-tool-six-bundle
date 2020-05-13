# Shopware Developer Tool
=======================

Todos: 
- [ ] Change readme
- [ ] Change composer version
- [ ] publish to packagist
- [ ] write tests
- [ ] enable travis CI
- [ ] add other userfull stuff

The Bundle provides the default implementation of `enm/shopware-sdk` with `guzzlehttp/guzzle` and `jms/serializer` as service.

[![Build Status](https://travis-ci.com/MarcoFaul/sw-dev-tool-six-bundle.svg?branch=master)](https://travis-ci.com/MarcoFaul/sw-dev-tool-six-bundle)

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
        enable_dal_caching: false:

## Tests

    php vendor/bin/simple-phpunit
