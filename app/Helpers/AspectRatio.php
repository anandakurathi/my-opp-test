<?php


namespace App\Helpers;


class AspectRatio
{
    /**
     * Convert string format aspect ratio to value
     * @param $ratio
     * @return float
     */
    public static function genericRatioCalculator($ratio)
    {
        $ratio = explode(':', $ratio);
        return round(($ratio[1] / $ratio[0]), 2);
    }

    /**
     * calculate aspect ratio based on height and width
     * @param $height
     * @param $weight
     * @return float
     */
    public static function HeightWidthToAspactRatio($height, $weight): float
    {
        return round(($height / $weight), 2);
    }
}
