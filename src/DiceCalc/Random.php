<?php

namespace DiceCalc;

/**
 * Class Random
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class Random {
    public static $queue = null;
    public static $queue_list = [];

    public static function get($min, $max) {
        if(is_callable(self::$queue)) {
            $test_fn = self::$queue;
            $result = $test_fn($min, $max);
        }
        else {
            $result = mt_rand($min, $max);
        }
        return $result;
    }

    public static function set_queue($queue) {
        self::$queue = $queue;
    }

    public static function clear_queue() {
        self::$queue = null;
    }

    public static function queue_up() {
        $numbers = func_get_args();
        self::$queue_list = self::$queue_list + $numbers;
        self::set_queue(function($min, $max){
                return array_shift(self::$queue_list);
            });
    }

    public static function queue_min() {
        self::set_queue(function($min, $max) {
            return $min;
        });
    }

    public static function queue_max() {
        self::set_queue(function($min, $max) {
            return $max;
        });
    }
} 