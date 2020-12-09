<?php

namespace Mmc\VersionManipulator\Component\Common;

use Mmc\VersionManipulator\Component\Model;

class AnotherVersion extends Model\AbstractVersion
{
    public function getSupportedContainerClass(): string
    {
        return SomeContainerVersion::class;
    }

    public function copy(Model\VersionInterface $version): Model\VersionInterface
    {
        return $this;
    }
}
