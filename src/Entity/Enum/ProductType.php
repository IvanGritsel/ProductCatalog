<?php

namespace App\Entity\Enum;

enum ProductType: int
{
    case TV = 1;
    case LAPTOP = 2;
    case PHONE = 3;
    case FRIDGE = 4;
}
