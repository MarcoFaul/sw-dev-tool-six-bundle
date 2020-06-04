<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\DependencyInjection;


use MarcoFaul\SwDevToolSixBundle\DependencyInjection\SwDevToolSixExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SwDevToolSixExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function load()
    {
        $containerBuilder = new ContainerBuilder();
        $configs = [
            'access_token_ttl' => 'P1W',
            'enable_dal_caching' => false,
            'shopware' => [
                'run_wizard' => true,
                'auto_update' => false,
                'api_auth_require' => false,
                'storefront_csrf' => false
            ],
            'twig' => [
                'debug' => true
            ]
        ];
        $swDevToolSixExtension = new SwDevToolSixExtension();
        $swDevToolSixExtension->load([$configs], $containerBuilder);


        $expected = [
            'sw_dev_tool_six.twig.debug' => true,
            'sw_dev_tool_six.access_token_ttl' => 'P1W',
            'sw_dev_tool_six.enable_dal_caching' => false,
            'sw_dev_tool_six.shopware.run_wizard' => true,
            'sw_dev_tool_six.shopware.auto_update' => false,
            'sw_dev_tool_six.shopware.api_auth_require' => false,
            'sw_dev_tool_six.shopware.storefront_csrf' => false,
        ];

        $this->assertEquals($expected, $containerBuilder->getParameterBag()->all());
    }
}
