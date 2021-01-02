<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Common;
use Mmc\VersionManipulator\Component\Exception;
use Mmc\VersionManipulator\Component\Model;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeleterTest extends TestCase
{
    protected $creator;
    protected $deleter;

    public function setUp(): void
    {
        $this->creator = new Creator();
        $this->deleter = new Deleter();
    }

    public function testDeleteOnEmptyContainer()
    {
        $container = new Common\SomeContainerVersion();

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage('nothing_to_delete');

        $version = $this->deleter->delete($container);
    }

    public function testDelete()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);

        $version = $this->deleter->delete($container);

        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::DELETED, $version->getStatus());

        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::DELETED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DRAFT]));

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage('nothing_to_delete');

        $version = $this->deleter->delete($container);
    }

    public function testDeleteWithVersions()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);
        $container->addVersion((new Common\SomeVersion())->setStatus(Model\Status::PUBLISHED));
        $container->addVersion((new Common\SomeVersion())->setStatus(Model\Status::ARCHIVED));

        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DELETED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::DRAFT]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::ARCHIVED]));

        $version = $this->deleter->delete($container);

        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::DELETED, $version->getStatus());

        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::DELETED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DRAFT]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
    }
}
