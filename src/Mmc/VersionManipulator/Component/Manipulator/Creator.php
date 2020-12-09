<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception\InvalidArgumentException;
use Mmc\VersionManipulator\Component\Model\Status;
use Mmc\VersionManipulator\Component\Model\VersionContainerInterface;

class Creator
{
    public function create(string $className): VersionContainerInterface
    {
        if (!is_subclass_of($className, VersionContainerInterface::class, true)) {
            throw new InvalidArgumentException(sprintf('This class "%s" is not a VersionContainerInterface', $className));
        }

        $container = new $className();

        $versionsClass = $container->getSupportedClass();
        $version = new $versionsClass();
        $version->setContainer($container)
            ->setStatus(Status::DRAFT)
            ;

        return $container;
    }
}
