<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Common;
use Mmc\VersionManipulator\Component\Model;
use PHPUnit\Framework\TestCase;

class CreatorTest extends TestCase
{
    protected $creator;

    public function setUp(): void
    {
        $this->creator = new Creator();
    }

    public function testCreate()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);

        $this->assertCount(1, $container->getVersions());
        $version = $container->getMainVersion();
        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::DRAFT, $version->getStatus());
    }
}
