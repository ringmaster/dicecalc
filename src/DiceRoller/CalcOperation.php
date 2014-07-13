<?php

namespace DiceRoller;

class CalcOperation
{
    /**
     * @param string $operator
     * @param $operand2
     * @param $operand1
     * @return bool|numeric
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
        }
    }

    /**
     * @param $r
     * @return numeric|string
     * @throws \Exception
     */
    public static function reduce($r)
    {
        if ($r instanceof Calc) {
            return $r->calc();
        }
        if (is_numeric($r)) {
            return $r;
        }
        throw new \Exception('This is not a number');
    }

    /**
     * @param numeric $r1
     * @param numeric $r2
     * @return bool|numeric
     */
    public static function add($r1, $r2)
    {
        try {
            return self::reduce($r1) + self::reduce($r2);
        } catch (\Exception $e) {
            return $r1 . $r2;
        }
    }

    /**
     * @param numeric $r1
     * @param numeric $r2
     * @return bool|numeric
     */
    public static function multiply($r1, $r2)
    {
        if (is_numeric($r1) && is_numeric($r2)) {
            return $r1 * $r2;
        }
    }

    /**
     * @param numeric $r1
     * @param numeric $r2
     * @return bool|numeric
     */
    public static function subtract($r1, $r2)
    {
        if (is_numeric($r1) && is_numeric($r2)) {
            return $r1 - $r2;
        }
    }

    /**
     * @param numeric $r1
     * @param numeric $r2
     * @return bool|float
     */
    public static function divide($r1, $r2)
    {
        if (is_numeric($r1) && is_numeric($r2)) {
            return $r1 / $r2;
        }
    }

    /**
     * @param number $r1
     * @param number $r2
     * @return bool|number
     */
    public static function exponent($r1, $r2)
    {
        if (is_numeric($r1) && is_numeric($r2)) {
            return pow($r1, $r2);
        }
    }

    /**
     * @param number $r1
     * @param number $r2
     * @return bool
     */
    public static function greaterthan($r1, $r2)
    {
        try {
            return self::reduce($r1) > self::reduce($r2);
        } catch (\Exception $e) {
            return $r1 > $r2;
        }
    }

    /**
     * @param number $r1
     * @param number $r2
     * @return bool
     */
    public static function lessthan($r1, $r2)
    {
        if (is_numeric($r1) && is_numeric($r2)) {
            return ($r1 < $r2);
        }
    }

    /**
     * @param number $r1
     * @param number $r2
     * @return bool
     */
    public static function equalto($r1, $r2)
    {
        if (is_numeric($r1) && is_numeric($r2)) {
            return ($r1 == $r2);
        }
    }
}
