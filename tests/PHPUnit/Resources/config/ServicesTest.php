<?php declare(strict_types=1);


namespace MarcoFaul\SwDevToolSixBundle\Tests\PHPUnit\Resources\config;


use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Util\XmlUtils;

class ServicesTest extends TestCase
{
    private const NS = 'http://symfony.com/schema/dic/services';

    /** @var \DOMXPath */
    private $domxpath;

    /**
     * initialize all services once
     */
    public function setUp(): void
    {
        $dom = XmlUtils::loadFile(__DIR__ . '/../../../../src/Resources/config/services.xml');
        $domxpath = new \DOMXPath($dom);
        $domxpath->registerNamespace('container', self::NS);

        $this->domxpath = $domxpath;
    }

    /**
     * @test
     * @group configuration
     */
    public function defaults(): void
    {
        $this->assertEquals(1, $this->domxpath->query('//container:services/container:defaults[@public="true"]')->count());
        $this->assertEquals(1, $this->domxpath->query('//container:services/container:defaults[@autowire="true"]')->count());
    }
}
