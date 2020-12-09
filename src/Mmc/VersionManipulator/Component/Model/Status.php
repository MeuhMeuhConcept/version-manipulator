<?php

namespace Mmc\VersionManipulator\Component\Model;

use Greg0ire\Enum\AbstractEnum;

final class Status extends AbstractEnum
{
    const ARCHIVED = 'archived';
    const DRAFT = 'draft';
    const PUBLISHED = 'published';
    const DELETED = 'deleted';
}
