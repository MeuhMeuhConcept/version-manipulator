<?php

namespace Mmc\VersionManipulator\Component\Model;

use Mmc\VersionManipulator\Component\Exception\InvalidArgumentException;

abstract class AbstractVersion implements VersionInterface
{
    abstract public function getSupportedContainerClass(): string;

    abstract public function copy(VersionInterface $version): VersionInterface;

    protected $status;

    protected $container;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus($status): VersionInterface
    {
        if (!Status::isValidValue($status)) {
            throw new InvalidArgumentException('Invalid Status');
        }

        $this->status = $status;

        return $this;
    }

    public function getContainer(): VersionContainerInterface
    {
        return $this->container;
    }

    public function setContainer(VersionContainerInterface $container = null): VersionInterface
    {
        $supported = $this->getSupportedContainerClass();
        if (null !== $container && !$container instanceof $supported) {
            throw new InvalidArgumentException('The container is not a valid VersionContainer (only '.$this->getSupportedContainerClass().' supported)');
        }

        if ($this->container && $this->container != $container) {
            $this->container->removeVersion($this);
        }

        if ($container && $this->container != $container) {
            $this->container = $container;

            $container->addVersion($this);
        }

        return $this;
    }
}
