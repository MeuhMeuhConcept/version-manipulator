<?php

namespace Mmc\VersionManipulator\Component\Model;

interface VersionInterface
{
    public function copy(self $version): self;

    public function getStatus(): string;

    public function setStatus($status): self;

    public function setContainer(VersionContainerInterface $container = null): self;

    public function getContainer(): VersionContainerInterface;

    public function getValidationGroups(): array;
}
