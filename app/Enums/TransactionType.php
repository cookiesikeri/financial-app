<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DEBIT()
 * @method static static CREDIT()
 */
final class TransactionType extends Enum
{
    const DEBIT =   'Debit';
    const CREDIT =   'Credit';
}
