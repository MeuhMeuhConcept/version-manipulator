<?php

namespace Mmc\VersionManipulator\Component\Exception;

use Symfony\Component\Validator\Exception\ValidationFailedException;

class NotValidatableException extends ValidationFailedException implements VersionManipulatorException
{
}
