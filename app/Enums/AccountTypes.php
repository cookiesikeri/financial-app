<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static UNVERIFIED_USER()
 * @method static static ORDINARY_USER()
 * @method static static CLASSIC_USER()
 * @method static static PREMIUM_USER()
 */
final class AccountTypes extends Enum
{
    const UNVERIFIED_USER = 1;
    const ORDINARY_USER =  2;
    const CLASSIC_USER =  3;
    const PREMIUM_USER =  4;
}
