<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception;
use Mmc\VersionManipulator\Component\Common;
use Mmc\VersionManipulator\Component\Model;
use PHPUnit\Framework\TestCase;

class DraftCreatorTest extends TestCase
{
    protected $creator;
    protected $draftCreator;

    public function setUp(): void
    {
        $this->creator = new Creator();
        $this->draftCreator = new DraftCreator();
    }

    public function testCreateOnEmptyContainer()
    {
        $container = new Common\SomeContainerVersion();

        $this->expectException(Exception\EmptyContainerException::class);

        $version = $this->draftCreator->create($container);
    }

    public function testCreateWithDraft()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);

        $this->expectException(Exception\DraftAlreadyExistsException::class);

        $version = $this->draftCreator->create($container);
    }

    public function testCreate()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);
        $version = $container->getMainVersion();
        $version->setStatus(Model\Status::PUBLISHED);
        $version->setName('foo:bar');

        $version = $this->draftCreator->create($container);

        $this->assertCount(2, $container->getVersions());
        $version = $container->getMainVersion();
        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::PUBLISHED, $version->getStatus());
        $version = $container->getDraft();
        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::DRAFT, $version->getStatus());

        $this->assertEquals('foo:bar', $version->getName());
    }
}
