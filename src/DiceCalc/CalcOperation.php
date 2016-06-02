<?php

namespace DiceCalc;

/**
 * Class CalcOperation
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class CalcOperation
{

    static protected $operators = [
        '+' => 'add',
        '*' => 'multiply',
        '=' => 'equalto',
        '<' => 'lessthan',
        '>' => 'greaterthan',
        '^' => 'exponent',
        '/' => 'divide',
        '-' => 'subtract',
    ];

    /**
     * @param  string $operator
     * @param $operand2
     * @param $operand1
     *
     * @throws \Exception
     * @return bool|number
     */
    public static function calc($operator, $operand1, $operand2)
    {
        if(isset(static::$operators[$operator])) {
            return call_user_func(array('self', static::$operators[$operator]), self::reduce($operand1), self::reduce($operand2));
        }
        throw new \Exception('Unknown operator "' . $operator . '".');
    }

    /**
     * @param $operand
     *
     * @return number|string
     * @throws \Exception
     */
    public static function reduce($operand)
    {
        if(is_numeric($operand)) {
            return $operand;
        }
        elseif ($operand instanceof Calc) {
            return $operand();
        }
        throw new \Exception('This is not a number');
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    protected static function add($operand1, $operand2)
    {
        return $operand1 + $operand2;
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    protected static function multiply($operand1, $operand2)
    {
        return $operand1 * $operand2;
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    protected static function subtract($operand1, $operand2)
    {
        return $operand1 - $operand2;
    }

    /**
     * @param  number    $operand1
     * @param  number    $operand2
     *
     * @return bool|number
     */
    protected static function divide($operand1, $operand2)
    {
        return $operand1 / $operand2;
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    protected static function exponent($operand1, $operand2)
    {
        return pow($operand1, $operand2);
    }

    /**
     * @param  number $operand1
     * @param  number $operand2
     *
     * @return bool
     */
    protected static function greaterthan($operand1, $operand2)
    {
        return $operand1 > $operand2;
    }

    /**
     * @param  number $operand1
     * @param  number $operand2
     *
     * @return bool
     */
    protected static function lessthan($operand1, $operand2)
    {
        return ($operand1 < $operand2);
    }

    /**
     * @param  number $operand1
     * @param  number $operand2
     *
     * @return bool
     */
    protected static function equalto($operand1, $operand2)
    {
        return ($operand1 == $operand2);
    }
}
