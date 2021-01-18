<?php

namespace Mmc\VersionManipulator\Component\Manipulator;

use Mmc\VersionManipulator\Component\Exception\NotValidatableException;
use Mmc\VersionManipulator\Component\Exception\RuntimeException;
use Mmc\VersionManipulator\Component\Model\Status;
use Mmc\VersionManipulator\Component\Model\VersionContainerInterface;
use Mmc\VersionManipulator\Component\Model\VersionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Publisher
{
    protected $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    public function publish(VersionContainerInterface $container): VersionInterface
    {
        $draftVersion = $container->getDraft();

        if (!$draftVersion || Status::DRAFT !== $draftVersion->getStatus()) {
            throw new RuntimeException('nothing_to_validate');
        }

        $errors = $this->validator->validate(
            $draftVersion,
            null,
            $draftVersion->getValidationGroups()
        );

        if (count($errors)) {
            throw new NotValidatableException($draftVersion, $errors);
        }

        $validVersions = $container->getVersionsByStatus(Status::PUBLISHED);
        foreach ($validVersions as $validVersion) {
            $validVersion->setStatus(Status::ARCHIVED);
        }

        $draftVersion->setStatus(STATUS::PUBLISHED);

        return $draftVersion;
    }
}
