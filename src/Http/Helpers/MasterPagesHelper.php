<?php
/**
 * Created by PhpStorm.
 * User: Furkan
 * Date: 18.8.2015
 * Time: 10:45
 */

namespace Whole\Core\Http\Helpers;


class MasterPagesHelper {


    public static function inValue($arrays,$value)
    {
        foreach($arrays as $array)
        {
            if ($array->pivot->field_name == $value){ return true;}
        }

        return false;

    }
}
