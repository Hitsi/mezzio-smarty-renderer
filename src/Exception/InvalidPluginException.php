<?php

declare(strict_types=1);

namespace Hitsi\Mezzio\SmartyRenderer\Exception;

use DomainException;
use Psr\Container\ContainerExceptionInterface;

class InvalidPluginException extends DomainException implements
    ContainerExceptionInterface,
    ExceptionInterface
{
}
