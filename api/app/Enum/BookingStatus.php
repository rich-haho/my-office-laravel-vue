<?php

namespace App\Enum;

use Spatie\Enum\Enum;

/**
* @method static self pending()
* @method static self accepted()
* @method static self prepaid()
* @method static self paid()
* @method static self processed()
* @method static self delivered()
* @method static self refused()
*/

class BookingStatus extends Enum
{
}
