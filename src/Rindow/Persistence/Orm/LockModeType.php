<?php
namespace Rindow\Persistence\Orm;

interface LockModeType
{
    const NONE               = "NONE";
    const OPTIMISTIC         = "OPTIMISTIC";
    const OPTIMISTIC_FORCE_INCREMENT = "OPTIMISTIC_FORCE_INCREMENT";
    const PRESSIMISTIC_FORCE_INCREMENT = "PRESSIMISTIC_FORCE_INCREMENT";
    const PRESSIMISTIC_READ  = "PRESSIMISTIC_READ";
    const PRESSIMISTIC_WRITE = "PRESSIMISTIC_WRITE";
    const READ               = "READ";
    const WRITE              = "WRITE";
}