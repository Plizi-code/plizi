<?php


namespace Domain\Pusher\Helpers;


class ArrayUtils
{
    public static function objArraySearch($array, $index, $value)
    {
        $collection = [];
        foreach($array as $arrayInf) {
            if($arrayInf->{$index} == $value) {
                $collection[] = $arrayInf;
            }
        }
        return $collection;
    }
}
