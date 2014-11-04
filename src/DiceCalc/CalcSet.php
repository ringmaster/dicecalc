<?php

namespace DiceCalc;

/**
 * Class CalcSet
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class CalcSet {
    protected $values = [];
    protected $label;

    public function __construct($set_value) {
        if (is_array($set_value)) {
            $this->values       = $set_value;
            $this->saved_values = $this->values;
            $this->label        = '[' . implode(', ', $set_value) . ']';
        } else {
            $this->label = $set_value;
            preg_match('%^(?P<multiple>\d*)\[(?P<set>[^\]]+)\]$%i', $set_value, $matches);
            $set_value = $matches['set'];
            $values    = explode(',', $set_value);
            for ($z = 0; $z < max(1, intval($matches['multiple'])); $z ++) {
                foreach ($values as $set_value) {
                    $this->values[] = $set_value;
                }
            }
            $this->saved_values = $this->values;
            foreach ($this->values as $k => $set_value) {
                $calc             = new Calc($set_value);
                $this->values[$k] = $calc();
            }
            foreach ($this->values as $k => $value) {
                $calc             = new Calc($value);
                $this->values[$k] = $calc();
            }
        }
    }

    public function __toString() {
        $out = [];
        foreach (array_keys($this->saved_values) as $key) {
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

    public function calc($operator, $operand) {
        $out = [];
        foreach ($this->values as $value) {
            $out[] = CalcOperation::calc($operator, $operand, $value);
        }

        return new CalcSet($out);
    }

    public function rcalc($operator, $operand) {
        $out = [];
        foreach ($this->values as $value) {
            $out[] = CalcOperation::calc($operator, $value, $operand);
        }

        return new CalcSet($out);
    }

    public function mcalc($operator, $operand)
    {
        $out = [];
        foreach ($this->values as $value) {
            $out[] = $operand->rcalc($operator, $value);
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
