<?php

namespace DiceCalc;

/**
 * Class CalcDice
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class CalcDice extends CalcSet
{
    protected $saved_values = [];

    public function __construct($set_value)
    {
        $this->values = [];
        $this->label = $set_value;
        preg_match('/' . Calc::DICE_REGEX . '/ix', $set_value, $matches);
        if (intval($matches['multiple']) == 0 && $matches['multiple'] != '0') {
            $matches['multiple'] = 1;
        }
        $matches += ['matches'=>'', 'openroll' => '', 'reroll' => '', 'keep' => ''];
        for ($z = 0; $z < $matches['multiple']; $z++) {
            $keep = true;

            $newval = $this->rolltype($matches['dietype']);

            if ($matches['reroll'] != '') {
                $gtlt = $matches['rerolleval'];
                $range = intval($matches['rerolllimit']);
                if ($gtlt == '<' && $newval < $range) {
                    $keep = false;
                    $z--;
                }
                if ($gtlt == '>' && $newval > $range) {
                    $keep = false;
                    $z--;
                }
            }

            if ($matches['openroll'] != '') {
                $gtlt = $matches['openrolleval'];
                $range = intval($matches['openrolllimit']);
                $addvals = [$newval];
                $addval = $newval;

                $evals = [
                    '<' => function($addval, $range) { return $addval < $range; },
                    '>' => function($addval, $range) { return $addval > $range; },
                    '=' => function($addval, $range) { return $addval == $range; },
                ];

                while(isset($evals[$gtlt]) && $evals[$gtlt]($addval, $range)) {
                    $addval = $this->rolltype($matches['dietype']);
                    $addvals[] = $addval;
                }

                $newval = new Calc(implode('+', $addvals));
                $newval = $newval();
            }

            if ($keep) {
                $this->values['_' . count($this->values)] = $newval;
            }
        }

        $this->saved_values = $this->values;

        if ($matches['keep'] != '') {
            $gtlt = $matches['keepeval'];
            $range = intval($matches['keeprange']);
            foreach ($this->values as $k => $set_value) {
                $av = $set_value instanceof Calc ? $set_value() : $set_value;
                if ($gtlt == '>' && $av <= $range) {
                    unset($this->values[$k]);
                }
                if ($gtlt == '<' && $av >= $range) {
                    unset($this->values[$k]);
                }
            }
        }

        asort($this->values);
        if (isset($matches['highdice']) && $matches['highdice'] != '') {
            $this->values = array_slice($this->values, -intval($matches['highdice']), null, true);
        }
        if (isset($matches['lowdice']) && $matches['lowdice'] != '') {
            $this->values = array_slice($this->values, 0, intval($matches['lowdice']), true);
        }
    }

    public function rolltype($dietype)
    {
        if (is_numeric($dietype)) {
            $newval = Random::get(1, $dietype);
        } elseif ($dietype == 'f') {
            $newval = Random::get(-1, 1);
        } elseif ($dietype == '%') {
            $newval = Random::get(1, 100);
        } elseif ($dietype[0] == '[') {
            $dietype = trim($dietype, '[]');
            $opts = explode(',', $dietype);
            $newval = $opts[Random::get(0, count($opts)-1)];
        } else {
            var_dump($dietype);
            $newval = 'unknown';
        }

        return $newval;
    }

    public function __toString()
    {
        $out = [];
        foreach (array_keys($this->saved_values) as $key) {
            $vout = $this->saved_values[$key];
            if ($vout === true) {
                $vout = '<span class="true">true</span>';
            }
            if ($vout === false) {
                $vout = '<span class="false">false</span>';
            }
            if (isset($this->values[$key])) {
                $out[] = $vout;
            } else {
                if ($vout instanceof Calc) {
                    $out[] = '<s>' . $vout() . '</s>';
                } else {
                    $out[] = '<s>' . $vout . '</s>';
                }
            }
        }

        $result = '[' . $this->label . ':';
        $comma = '';
        foreach ($out as $o) {
            if ($o instanceof Calc) {
                $result .= $comma . $o();
            } else {
                $result .= $comma . $o;
            }
            $comma = ' + ';
        }
        $result .= ']';

        return $result;
    }
}
