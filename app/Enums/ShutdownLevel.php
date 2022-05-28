<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NOT_SHUTDOWN()
 * @method static static USER_SHUTDOWN()
 * @method static static ADMIN_SHUTDOWN()
 */
final class ShutdownLevel extends Enum
{
    const NOT_SHUTDOWN =   0;
    const USER_SHUTDOWN =   1;
    const ADMIN_SHUTDOWN = 2;
}
