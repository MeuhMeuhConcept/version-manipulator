<?php

namespace Mmc\VersionManipulator\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Mmc\VersionManipulator\Component\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractVersionContainer implements VersionContainerInterface
{
    abstract public function getSupportedClass(): string;

    protected $versions;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function getVersionsByStatus($status): Collection
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        $versionsByStatus = new ArrayCollection();

        foreach ($this->versions as $version) {
            if (in_array($version->getStatus(), $status)) {
                $versionsByStatus->add($version);
            }
        }

        return $versionsByStatus;
    }

    public function addVersion(VersionInterface $version): VersionContainerInterface
    {
        $this->checkVersion($version);

        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
            $version->setContainer($this);
        }

        return $this;
    }

    public function removeVersion(VersionInterface $version): VersionContainerInterface
    {
        if ($this->versions->contains($version)) {
            $this->versions->removeElement($version);
            $version->setContainer(null);
        }

        return $this;
    }

    private function checkVersion(VersionInterface $version): self
    {
        $class = $this->getSupportedClass();
        if (!$version instanceof $class) {
            throw new InvalidArgumentException('Bad type, '.$class.' expected.');
        }

        return $this;
    }

    public function getMainVersion(): ?VersionInterface
    {
        return $this->getFirstVersionInOrder([Status::PUBLISHED, Status::DRAFT]);
    }

    public function getValid(): ?VersionInterface
    {
        return $this->getFirstVersionInOrder([Status::PUBLISHED]);
    }

    /**
     * @Assert\Valid
     */
    public function getDraft(): ?VersionInterface
    {
        return $this->getFirstVersionInOrder([Status::DRAFT]);
    }

    protected function getFirstVersionInOrder($status): ?VersionInterface
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        foreach ($status as $s) {
            $versions = $this->getVersionsByStatus($s);

            if ($versions->count()) {
                return $versions->first();
            }
        }

        return null;
    }
}
