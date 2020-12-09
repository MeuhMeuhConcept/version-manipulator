<?php

namespace Mmc\VersionManipulator\Component\Model;

use Doctrine\Common\Collections\Collection;

interface VersionContainerInterface
{
    public function getVersions(): Collection;

    public function getVersionsByStatus($status): Collection;

    public function addVersion(VersionInterface $version): self;

    public function removeVersion(VersionInterface $version): self;

    public function getMainVersion(): ?VersionInterface;

    public function getValid(): ?VersionInterface;

    public function getDraft(): ?VersionInterface;
}
