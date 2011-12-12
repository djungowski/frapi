<?php
namespace Score11\Frapi;

class Thumb
{
    const THUMB_UP = 6.5;
    const THUMB_AVG = 3.5;
    
    public function getTrend($ratings, $average)
    {
        if ($ratings) {
            if ($average > self::THUMB_UP) {
                return 'up';
            } elseif ($average >= self::THUMB_AVG) {
                return 'middle';
            } else {
                return 'down';
            }
        } else {
            return 'none';
        }
    }
}