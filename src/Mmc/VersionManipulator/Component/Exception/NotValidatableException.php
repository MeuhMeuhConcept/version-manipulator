<?php

namespace Mmc\VersionManipulator\Component\Exception;

class NotValidatableException extends \RuntimeException implements VersionManipulatorException
{
    protected $errors;

    public function __construct(
        $errors
    ) {
        parent::__construct();

        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
