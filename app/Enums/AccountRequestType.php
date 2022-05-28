<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Upgrade()
 * @method static static Edit()
 * @method static static Shutdown()
 * @method static static Unblock()
 */
final class AccountRequestType extends Enum
{
    const UPGRADE =   'Upgrade';
    const EDIT =   'Edit';
    const SHUTDOWN = 'Shutdown';
    const UNBLOCK = 'Unblock';
}
