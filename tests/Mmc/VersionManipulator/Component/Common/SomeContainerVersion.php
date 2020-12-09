<?php

namespace Mmc\VersionManipulator\Component\Common;

use Mmc\VersionManipulator\Component\Model;

class SomeContainerVersion extends Model\AbstractVersionContainer
{
    public function getSupportedClass(): string
    {
        return SomeVersion::class;
    }
}
