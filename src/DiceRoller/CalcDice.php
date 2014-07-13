<?php

namespace DiceRoller;

class CalcDice extends CalcSet
{
    public function __construct($v)
    {
        $this->values = array();
        $this->label = $v;
        preg_match('/' . Calc::DICE_REGEX . '/i', $v, $matches);
        if (intval($matches['multiple']) == 0 && $matches['multiple'] != '0') {
            $matches['multiple'] = 1;
        }
        $matches += array('matches'=>'', 'openroll' => '', 'reroll' => '', 'keep' => '');
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
                $addvals = array($newval);
                $addval = $newval;

                if ($gtlt == '<') {
                    while ($addval < $range) {
                        $addval = $this->rolltype($matches['dietype']);
                        $addvals[] = $addval;
                    }
                }
                if ($gtlt == '>') {
                    while ($addval > $range) {
                        $addval = $this->rolltype($matches['dietype']);
                        $addvals[] = $addval;
                    }
                }
                if ($gtlt == '=') {
                    while ($addval == $range) {
                        $addval = $this->rolltype($matches['dietype']);
                        $addvals[] = $addval;
                    }
                }
                $newval = new Calc(implode('+', $addvals));
                $newval = $newval->calc();
            }

            if ($keep) {
                $this->values['_' . count($this->values)] = $newval;
            }
        }

        $this->saved_values = $this->values;

        if ($matches['keep'] != '') {
            $gtlt = $matches['keepeval'];
            $range = intval($matches['keeprange']);
            foreach ($this->values as $k => $v) {
                $av = $v instanceof Calc ? $v->calc() : $v;
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
            $newval = rand(1, $dietype);
        } elseif ($dietype == 'f') {
            $newval = rand(-1, 1);
        } elseif ($dietype == '%') {
            $newval = rand(1, 100);
        } elseif ($dietype[0] == '[') {
            $dietype = trim($dietype, '[]');
            $opts = explode(',', $dietype);
            $newval = $opts[rand(0, count($opts)-1)];
        } else {
            var_dump($dietype);
        }

        return $newval;
    }

    public function __toString()
    {
        $out = array();
        foreach ($this->saved_values as $key => $value) {
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
                    $out[] = '<s>' . $vout->calc() . '</s>';
                } else {
                    $out[] = '<s>' . $vout . '</s>';
                }
            }
        }

        $result = '[' . $this->label . ':';
        $comma = '';
        foreach ($out as $o) {
            if ($o instanceof Calc) {
                $result .= $comma . $o->calc();
            } else {
                $result .= $comma . $o;
            }
            $comma = ' + ';
        }
        $result .= ']';

        return $result;
    }
}
