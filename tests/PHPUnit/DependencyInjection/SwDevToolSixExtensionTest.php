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
                'skip_first_run_wizard_client' => true,
                'enable_auto_update' => false,
                'enable_api_auth_require' => false,
                'enable_storefront_csrf' => false
            ]
        ];
        $swDevToolSixExtension = new SwDevToolSixExtension();
        $swDevToolSixExtension->load([$configs], $containerBuilder);


        $expected = [
            'sw_dev_tool_six.access_token_ttl' => 'P1W',
            'sw_dev_tool_six.enable_dal_caching' => false,
            'sw_dev_tool_six.shopware.skip_first_run_wizard_client' => true,
            'sw_dev_tool_six.shopware.enable_auto_update' => false,
            'sw_dev_tool_six.shopware.enable_api_auth_require' => false,
            'sw_dev_tool_six.shopware.enable_storefront_csrf' => false,
        ];

        $this->assertEquals($expected, $containerBuilder->getParameterBag()->all());
    }
}
