<?php

namespace DiceCalc;

/**
 * Class CalcSet
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class CalcSet
{
    protected $values = [];
    protected $label;

    public function __construct($v)
    {
        if (is_array($v)) {
            $this->values = $v;
            $this->saved_values = $this->values;
            $this->label = '[' . implode(', ', $v) . ']';
        } else {
            $this->label = $v;
            preg_match('%^(?P<multiple>\d*)\[(?P<set>[^\]]+)\]$%i', $v, $matches);
            $v = $matches['set'];
            $values = explode(',', $v);
            for ($z = 0; $z < max(1, intval($matches['multiple'])); $z++) {
                foreach ($values as $v) {
                    $this->values[] = $v;
                }
            }
            $this->saved_values = $this->values;
            foreach ($this->values as $k => $v) {
                $calc = new Calc($v);
                $this->values[$k] = $calc->calc();
            }
            foreach ($this->values as $k => $value) {
                $calc = new Calc($value);
                $this->values[$k] = $calc->calc();
            }
        }
    }
    public function __toString()
    {
        $out = [];
        foreach ($this->saved_values as $key => $value) {
            $vout = $this->values[$key];
            if (!isset($this->values[$key])) {
                $vout = $this->saved_values[$key];
            }
            if ($vout === true) {
                $vout = '<span class="true">true</span>';
            }
            if ($vout === false) {
                $vout = '<span class="false">false</span>';
            }
            if (isset($this->values[$key])) {
                $out[] = $vout;
            } else {
                $out[] = '<s>' . $vout . '</s>';
            }
        }
        $out = '[' . implode(', ', $out) . ']';

        return $out;
    }

    public function calc($operator, $operand)
    {
        $out = [];
        foreach ($this->values as $value) {
            $out[] = CalcOperation::calc($operator, $value, $operand);
        }

        return new CalcSet($out);
    }

    public function rcalc($operator, $operand)
    {
        $out = [];
        foreach ($this->values as $value) {
            $out[] = CalcOperation::calc($operator, $operand, $value);
        }

        return new CalcSet($out);
    }

    public function value()
    {
        $allnumeric = true;
        foreach ($this->values as $v) {
            if (is_numeric($v)) {
            } else {
                $allnumeric = false;
            }
        }

        if ($allnumeric) {
            return array_sum($this->values);
        } else {
            return $this;
        }
    }
}
