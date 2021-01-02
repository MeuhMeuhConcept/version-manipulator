<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception\RuntimeException;
use Mmc\VersionManipulator\Component\Model\Status;
use Mmc\VersionManipulator\Component\Model\VersionContainerInterface;
use Mmc\VersionManipulator\Component\Model\VersionInterface;

class Deleter
{
    public function delete(VersionContainerInterface $container): VersionInterface
    {
        $draftVersion = $container->getDraft();

        if (!$draftVersion || Status::DRAFT !== $draftVersion->getStatus()) {
            throw new RuntimeException('nothing_to_delete');
        }

        $draftVersion->setStatus(STATUS::DELETED);

        return $draftVersion;
    }
}
