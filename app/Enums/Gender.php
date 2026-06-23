<?php

namespace App\Enums;

use App\Enums\Concerns\ArrayableEnum;

enum Gender: string
{
    use ArrayableEnum;

    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
