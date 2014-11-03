<?php

namespace DiceCalc;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class CalcOperation
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class CalcOperation
{
    /**
     * @param  string $operator
     * @param $operand2
     * @param $operand1
     *
     * @throws \Exception
     * @return bool|number
     */
    public static function calc($operator, $operand2, $operand1)
    {
        switch ($operator) {
            case '+':
                return self::add($operand1, $operand2);
            case '*':
                return self::multiply($operand1, $operand2);
            case '-':
                return self::subtract($operand1, $operand2);
            case '/':
                return self::divide($operand1, $operand2);
            case '^':
                return self::exponent($operand1, $operand2);
            case '>':
                return self::greaterthan($operand1, $operand2);
            case '<':
                return self::lessthan($operand1, $operand2);
            case '=':
                return self::equalto($operand1, $operand2);
            default:
                throw new \Exception('Unknown operator "' . $operator . '".');
        }
    }

    /**
     * @param $operand
     *
     * @return number|string
     * @throws \Exception
     */
    public static function reduce($operand)
    {
        if ($operand instanceof Calc) {
            return $operand();
        }
        if (is_numeric($operand)) {
            return $operand;
        }
        throw new \Exception('This is not a number');
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    public static function add($operand1, $operand2)
    {
        if (!is_numeric($operand1)) {
            try {
                $operand1 = self::reduce($operand1);
            } catch (\Exception $e) {
                return false;
            }
        }

        if (!is_numeric($operand2)) {
            try {
                $operand2 = self::reduce($operand2);
            } catch (\Exception $e) {
                return false;
            }
        }

        return $operand1 + $operand2;
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    public static function multiply($operand1, $operand2)
    {
        if (is_numeric($operand1) && is_numeric($operand2)) {
            return $operand1 * $operand2;
        }

        return false;
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    public static function subtract($operand1, $operand2)
    {
        if (is_numeric($operand1) && is_numeric($operand2)) {
            return $operand1 - $operand2;
        }

        return false;
    }

    /**
     * @param  number    $operand1
     * @param  number    $operand2
     *
     * @return bool|number
     */
    public static function divide($operand1, $operand2)
    {
        if (is_numeric($operand1) && is_numeric($operand2)) {
            return $operand1 / $operand2;
        }

        return false;
    }

    /**
     * @param  number      $operand1
     * @param  number      $operand2
     *
     * @return bool|number
     */
    public static function exponent($operand1, $operand2)
    {
        if (is_numeric($operand1) && is_numeric($operand2)) {
            return pow($operand1, $operand2);
        }

        return false;
    }

    /**
     * @param  number $operand1
     * @param  number $operand2
     *
     * @return bool
     */
    public static function greaterthan($operand1, $operand2)
    {
        if (!is_numeric($operand1)) {
            try {
                $operand1 = self::reduce($operand1);
            } catch (\Exception $e) {
                return false;
            }
        }

        if (!is_numeric($operand2)) {
            try {
                $operand2 = self::reduce($operand2);
            } catch (\Exception $e) {
                return false;
            }
        }

        return $operand1 > $operand2;
    }

    /**
     * @param  number $operand1
     * @param  number $operand2
     *
     * @return bool
     */
    public static function lessthan($operand1, $operand2)
    {
        if (is_numeric($operand1) && is_numeric($operand2)) {
            return ($operand1 < $operand2);
        }

        return false;
    }

    /**
     * @param  number $operand1
     * @param  number $operand2
     *
     * @return bool
     */
    public static function equalto($operand1, $operand2)
    {
        if (is_numeric($operand1) && is_numeric($operand2)) {
            return ($operand1 == $operand2);
        }

        return false;
    }
}
