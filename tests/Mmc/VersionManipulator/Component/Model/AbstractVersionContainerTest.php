<?php

namespace Mmc\VersionManipulator\Component\Model;

use Mmc\VersionManipulator\Component\Common;
use Mmc\VersionManipulator\Component\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AbstractVersionContainerTest extends TestCase
{
    protected $container;

    public function setUp(): void
    {
        $this->container = $this->getMockForAbstractClass(AbstractVersionContainer::class);

        $this->container->expects($this->any())
            ->method('getSupportedClass')
            ->will($this->returnValue(VersionInterface::class));
    }

    public function testAddVersions()
    {
        $this->assertCount(0, $this->container->getVersions());

        $version1 = $this->createMock(VersionInterface::class);
        $this->container->addVersion($version1);
        $this->assertCount(1, $this->container->getVersions());

        $this->container->addVersion($version1);
        $this->assertCount(1, $this->container->getVersions());

        $version2 = $this->createMock(VersionInterface::class);
        $this->container->addVersion($version2);
        $this->assertCount(2, $this->container->getVersions());
    }

    public function testRemoveVersions()
    {
        $this->assertCount(0, $this->container->getVersions());

        $version1 = $this->createMock(VersionInterface::class);
        $this->container->addVersion($version1);
        $this->assertCount(1, $this->container->getVersions());

        $this->container->removeVersion($version1);
        $this->assertCount(0, $this->container->getVersions());

        $version1 = $this->createMock(VersionInterface::class);
        $this->container->addVersion($version1);
        $this->assertCount(1, $this->container->getVersions());

        $version2 = $this->createMock(VersionInterface::class);
        $this->container->addVersion($version2);
        $this->assertCount(2, $this->container->getVersions());

        $this->container->removeVersion($version1);
        $this->assertCount(1, $this->container->getVersions());
    }

    public function testAddVersionTypeError()
    {
        $version = new \stdClass();

        $this->expectException(\TypeError::class);

        $this->container->addVersion($version);
    }

    public function testAddVersionUnexcepted()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->container = $this->getMockForAbstractClass(AbstractVersionContainer::class);

        $this->container->expects($this->any())
            ->method('getSupportedClass')
            ->will($this->returnValue(Common\SomeVersion::class));

        $version = $this->createMock(Common\AnotherVersion::class);
        $this->container->addVersion($version);
    }
}
