<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Common;
use Mmc\VersionManipulator\Component\Exception;
use Mmc\VersionManipulator\Component\Model;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PublisherTest extends TestCase
{
    protected $creator;
    protected $draftCreator;
    protected $publisher;

    public function setUp(): void
    {
        $this->creator = new Creator();
        $this->draftCreator = new DraftCreator();
        $validator = $this->createMock(ValidatorInterface::class);
        $this->publisher = new Publisher($validator);

        $validator->expects($this->any())
            ->method('validate')
            ->will($this->returnValue([]));
    }

    public function testPublishOnEmptyContainer()
    {
        $container = new Common\SomeContainerVersion();

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage('nothing_to_validate');

        $version = $this->publisher->publish($container);
    }

    public function testPublishUnvalidate()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);

        $validator = $this->createMock(ValidatorInterface::class);
        $publisher = new Publisher($validator);

        $validator->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList([new ConstraintViolation('error1', '', [], null, 'foo', 'bar')])));

        $this->expectException(Exception\NotValidatableException::class);

        $version = $publisher->publish($container);
    }

    public function testPublish()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);

        $version = $this->publisher->publish($container);

        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::PUBLISHED, $version->getStatus());

        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DRAFT]));

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage('nothing_to_validate');

        $version = $this->publisher->publish($container);
    }

    public function testPublishWithVersions()
    {
        $container = $this->creator->create(Common\SomeContainerVersion::class);
        $version = $this->publisher->publish($container);

        $this->assertNotNull($version);
        $this->assertEquals(Model\Status::PUBLISHED, $version->getStatus());

        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DRAFT]));

        $oldVersion = $version;

        $version = $this->draftCreator->create($container);

        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::DRAFT]));

        $this->publisher->publish($container);

        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::ARCHIVED]));
        $this->assertCount(1, $container->getVersionsByStatus([Model\Status::PUBLISHED]));
        $this->assertCount(0, $container->getVersionsByStatus([Model\Status::DRAFT]));
        $this->assertEquals(Model\Status::PUBLISHED, $version->getStatus());
        $this->assertEquals(Model\Status::ARCHIVED, $oldVersion->getStatus());
    }
}
