<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception\DraftAlreadyExistsException;
use Mmc\VersionManipulator\Component\Exception\EmptyContainerException;
use Mmc\VersionManipulator\Component\Model\Status;
use Mmc\VersionManipulator\Component\Model\VersionContainerInterface;
use Mmc\VersionManipulator\Component\Model\VersionInterface;

class DraftCreator
{
    public function create(VersionContainerInterface $container): VersionInterface
    {
        $mainVersion = $container->getMainVersion();

        if (!$mainVersion) {
            throw new EmptyContainerException();
        }

        if (Status::DRAFT === $mainVersion->getStatus()) {
            throw new DraftAlreadyExistsException();
        }

        $class = get_class($mainVersion);
        $duplicate = new $class();
        $duplicate->setContainer($container);

        $duplicate->copy($mainVersion);
        $duplicate->setStatus(Status::DRAFT);

        return $duplicate;
    }
}
