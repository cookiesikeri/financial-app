<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Pending()
 * @method static static Accepted()
 * @method static static Rejected()
 */
final class AccountRequestAction extends Enum
{
    const PENDING =   0;
    const ACCEPTED =  1;
    const REJECTED = 2;
}
