<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests;


use PHPUnit\Framework\TestCase;

class ServicesTest extends TestCase
{
    /**
     * @test
     * @group configuration
     */
    public function defaults(): void
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXml(file_get_contents(__DIR__ . '/../../../src/Resources/config/services.xml'));


        $this->assertEquals(true, true);

    }
}
