<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception\RuntimeException;
use Mmc\VersionManipulator\Component\Model\Status;
use Mmc\VersionManipulator\Component\Model\VersionContainerInterface;
use Mmc\VersionManipulator\Component\Model\VersionInterface;

class Archiver
{
    public function archive(VersionContainerInterface $container): VersionInterface
    {
        $mainVersion = $container->getMainVersion();

        if (!$mainVersion || Status::PUBLISHED !== $mainVersion->getStatus()) {
            throw new RuntimeException('nothing_to_archive');
        }

        $mainVersion->setStatus(STATUS::ARCHIVED);

        return $mainVersion;
    }
}
