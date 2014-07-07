<?php

namespace DiceRoller;

class Calc
{

    const DICE_REGEX = '(?P<multiple>\d*)d(?P<dietype>\d+|f|\%|\[[^\]]+\])((?P<keep>k(?:eep)?(?P<keepeval>[<>])(?P<keeprange>\d+))|(?P<lowest>l(?:owest)?(?P<lowdice>\d+))|(?P<highest>h(?:ighest)?(?P<highdice>\d+))|(?P<reroll>r(?:eroll)?(?P<rerolleval>[<>])(?P<rerolllimit>\d+))|(?P<openroll>o(?:pen)?(?P<openrolleval>[<>=])(?P<openrolllimit>\d+))|(?P<flags>[z]+))*';

    private $ooo = array(
        '>' => 0,
        '<' => 0,
        '=' => 0,
        '-' => 10,
        '+' => 10,
        '*' => 20,
        '/' => 20,
        '^' => 30,
    );

    protected $expression;
    protected $rpn = array();
    protected $infix = array();

    function __construct($expression = '')
    {
        $this->expression = str_replace(' ', '', $expression);

        preg_match_all('%(?:(?P<dice>' . self::DICE_REGEX . ')|(?P<set>\d*\[[^\]]+\])|(?P<numeral>[\d\.]+)|(?P<operator>[+\-*^><=/])|(?P<variable>\$[a-z_]+)|(?P<parens>[()]))%i', $this->expression, $matches, PREG_SET_ORDER);

        $stack = array();

        foreach($matches as $match) {
            $match = array_filter($match);

            if(isset($match['numeral'])) {
                $this->rpn[] = $match['numeral'];
                $this->infix[] = $match['numeral'];
            }
            elseif(isset($match['dice'])) {
                $dice = new CalcDice($match['dice']);
                $this->rpn[] = $dice->value();
                $this->infix[] = $dice;
            }
            elseif(isset($match['set'])) {
                $this->rpn[] = new CalcSet($match['set']);
                $this->infix[] = end($this->rpn);
            }
            elseif(isset($match['operator'])) {
                while(count($stack) > 0 && end($stack) != '(' && $this->ooo[$match['operator']] <= $this->ooo[end($stack)]) {
                    $this->rpn[] = array_pop($stack);
                }
                $stack[] = $match['operator'];
                $this->infix[] = $match['operator'];
            }
            elseif(isset($match['variable'])) {
                $this->rpn[] = $match['variable'];
                $this->infix[] = end($this->rpn);
            }
            elseif(isset($match['parens'])) {
                $this->infix[] = $match['parens'];
                if($match['parens'] == '(') {
                    $stack[] = $match['parens'];
                }
                else {
                    while(count($stack) > 0 && end($stack) != '(') {
                        $this->rpn[] = array_pop($stack);
                    }
                    array_pop($stack);
                }
            }
            else {
                $stack = array('Invalid token:', $match);
                break;
            }
        }

        while(count($stack) > 0) {
            $this->rpn[] = array_pop($stack);
        }
    }

    function calc($vars = array() )
    {

        $stack = array();

        foreach($this->rpn as $step) {
            if(is_object($step) || !isset($this->ooo[$step])) {
                $stack[] = $step;
            }
            else {
                //echo "Operation: {$step}\n";
                //print_r($stack);
                $r1 = array_pop($stack);
                $r2 = array_pop($stack);

                if(is_numeric($r1) && is_numeric($r2)) {
                    $stack[] = CalcOperation::calc($step, $r1, $r2);
                }
                if($r1 instanceof CalcSet && is_numeric($r2)) {
                    $stack[] = $r1->calc($step, $r2);
                }
                if(is_numeric($r1) && $r2 instanceof CalcSet) {
                    $stack[] = $r2->rcalc($step, $r1);
                }
            }
        }

        if(count($stack) > 1) {
            return 'Missing operator near "' . $stack[1] . '".';
        }
        else {
            $out = reset($stack);
            return $out;
        }
    }

    function infix()
    {
        return implode(' ', $this->infix);
    }
}