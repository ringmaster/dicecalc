<?php
/**
 * Created by PhpStorm.
 * User: owinkler
 * Date: 11/2/14
 * Time: 11:43 PM
 */

namespace DiceCalc;


class Random {
    public static $queue = null;

    public static function get($min, $max) {
        if(is_callable(self::$queue)) {
            $test_fn = self::$queue;
            $result = $test_fn($min, $max);
        }
        else {
            $result = rand($min, $max);
        }
        return $result;
    }
} 