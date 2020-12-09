<?php

namespace Mmc\VersionManipulator\Component\Common;

use Mmc\VersionManipulator\Component\Model;

class SomeVersion extends Model\AbstractVersion
{
    protected $name;

    public function getSupportedContainerClass(): string
    {
        return SomeContainerVersion::class;
    }

    public function copy(Model\VersionInterface $version): Model\VersionInterface
    {
        $this->name = $version->name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
