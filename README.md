# Shopware Developer Tool
=======================
//@TODO: CHANGE ME

The Bundle provides the default implementation of `enm/shopware-sdk` with `guzzlehttp/guzzle` and `jms/serializer` as service.

[![Build Status](https://travis-ci.org/eosnewmedia/ShopwareSdkBundle.svg?branch=master)](https://travis-ci.org/eosnewmedia/ShopwareSdkBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/77b8f306-eefe-45a0-8500-c5ca6a7f56a0/mini.png)](https://insight.sensiolabs.com/projects/77b8f306-eefe-45a0-8500-c5ca6a7f56a0)

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
          new \Enm\Bundle\ShopwareSdk\EnmShopwareSdkBundle(),
        ];
         // ...
         
        return $bundles;
    }

## Configuration
Simply configure your shop connection over the global `config.yml`:

    enm_shopware_sdk:
        base_url: "http://your-shop.com"
        username: "test"
        api_key: "test"

This bundle overwrites the default naming strategy of the serializer with `IdenticalPropertyNamingStrategy`.

## Usage

    /** @var \Enm\Bundle\ShopwareSdk\EntryPoint $shopwareClient */
    $entryPoint = $container->get('enm.shopware.entry_point');

## Tests

    php vendor/bin/phpunit
