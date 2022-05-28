<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static UNVERIFIED_ACCOUNT()
 * @method static static ORDINARY_ACCOUNT()
 * @method static static CLASSIC_ACCOUNT()
 * @method static static PREMIUM_ACCOUNT()
 */
final class WithdrawalLimit extends Enum
{
    const UNVERIFIED_ACCOUNT =   0;
    const ORDINARY_ACCOUNT =   50000;
    const CLASSIC_ACCOUNT =   150000;
    const PREMIUM_ACCOUNT = 500000;
}
