<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception\NothingToDeleteException;
use Mmc\VersionManipulator\Component\Model\Status;
use Mmc\VersionManipulator\Component\Model\VersionContainerInterface;
use Mmc\VersionManipulator\Component\Model\VersionInterface;

class Deleter
{
    public function delete(VersionContainerInterface $container): VersionInterface
    {
        $draftVersion = $container->getDraft();

        if (!$draftVersion || Status::DRAFT !== $draftVersion->getStatus()) {
            throw new NothingToDeleteException();
        }

        $draftVersion->setStatus(STATUS::DELETED);

        return $draftVersion;
    }
}
