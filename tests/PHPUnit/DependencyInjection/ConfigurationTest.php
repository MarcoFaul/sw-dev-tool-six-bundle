<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\DependencyInjection;


use MarcoFaul\SwDevToolSixBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function getConfigTreeBuilder()
    {
        $configuration = new Configuration();
        $configTreeBuilder = $configuration->getConfigTreeBuilder();

        $this->assertEquals('sw_dev_tool_six', $configTreeBuilder->buildTree()->getName());

        $this->assertEquals(3, \count($configTreeBuilder->buildTree()->getChildren()));
        $this->assertEquals(4, \count($configTreeBuilder->buildTree()->getChildren()['shopware']->getChildren()));

    }
}
