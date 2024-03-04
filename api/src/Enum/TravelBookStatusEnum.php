<?php

namespace App\Enum;

enum TravelBookStatusEnum: string
{
    case INITIALIZED = 'INITIALIZED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case OK_TO_ACHIEVE = 'OK_TO_PRINT';

    case KO_UNPROCESSABLE = 'UNPROCESSABLE';

    case CANCELLED = 'CANCELLED';
    case DELETED = 'DELETED';
}
