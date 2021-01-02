<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Common;
use Mmc\VersionManipulator\Component\Exception;
use Mmc\VersionManipulator\Component\Model;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArchiverTest extends TestCase
{
    protected $creator;
    protected $archiver;

    public function setUp(): void
    {
        $this->creator = new Creator();
        $this->archiver = new Archiver();
    }

    public function testArchiveOnEmptyContainer()
    {
        $container = new Common\SomeContainerVersion();

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage('nothing_to_archive');

        $version = $this->archiver->archive($container);
    }

    public function testArchive()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);
        $version = $container->getMainVersion();
        $version->setStatus(Model\Status::PUBLISHED);

        $version = $this->archiver->archive($container);

        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::ARCHIVED, $version->getStatus());

        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DRAFT]));

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage('nothing_to_archive');

        $version = $this->archiver->archive($container);
    }

    public function testArchiveWithVersions()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);
        $container->addVersion((new Common\SomeVersion())->setStatus(Model\Status::PUBLISHED));
        $container->addVersion((new Common\SomeVersion())->setStatus(Model\Status::ARCHIVED));

        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::DRAFT]));

        $version = $this->archiver->archive($container);

        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::ARCHIVED, $version->getStatus());

        $this->assertCount(2, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::DRAFT]));
    }
}
