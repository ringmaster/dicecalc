<?php

namespace DiceCalc;

/**
 * Class Calc
 *
 * @package DiceCalc
 * @author  Owen Winkler <epithet@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 */
class Calc {

    const DICE_REGEX = '(?P<multiple>\d*)d(?P<dietype>\d+|f|\%|\[[^\]]+\])
(
(?P<keep>k(?:eep)?(?P<keepeval>[<>])(?P<keeprange>\d+))
|
(?P<lowest>l(?:owest)?(?P<lowdice>\d+))
|
(?P<highest>h(?:ighest)?(?P<highdice>\d+))
|
(?P<reroll>r(?:eroll)?(?P<rerolleval>[<>])(?P<rerolllimit>\d+))
|
(?P<openroll>o(?:pen)?(?P<openrolleval>[<>=])(?P<openrolllimit>\d+))
|
(?P<flags>[z]+)
)*';

    /**
     * @var array $ooo A list of operators with comparative order of operations
     */
    private $ooo = [
        '>' => 0,
        '<' => 0,
        '=' => 0,
        '-' => 10,
        '+' => 10,
        '*' => 20,
        '/' => 20,
        '^' => 30,
    ];

    protected $expression;
    protected $rpn = [];
    protected $infix = [];

    protected $stack = [];

    /**
     * Create a dice calculation
     *
     * @param string $expression An expression to calculate
     */
    public function __construct($expression = '') {
        $this->expression = str_replace(' ', '', $expression);

        preg_match_all('%(?:
            (?P<dice>' . self::DICE_REGEX . ')
            |
            (?P<set>\d*\[[^\]]+\])
            |
            (?P<numeral>[\d\.]+)
            |
            (?P<operator>[+\-*^><=/])
            |
            (?P<variable>\$[a-z_]+)
            |
            (?P<parens>[()])
        )%ix', $this->expression, $matches, PREG_SET_ORDER);

        $this->stack = [];

        foreach ($matches as $match) {
            $match = array_filter($match, function ($value) {
                return $value !== false && $value !== '';
            });

            if (isset($match['numeral'])) {
                $this->match_numeral($match['numeral']);
            } elseif (isset($match['dice'])) {
                $this->match_dice($match['dice']);
            } elseif (isset($match['set'])) {
                $this->match_set($match['set']);
            } elseif (isset($match['operator'])) {
                $this->match_operator($match['operator']);
            } elseif (isset($match['variable'])) {
                $this->match_variable($match['variable']);
            } elseif (isset($match['parens'])) {
                $this->match_parens($match['parens']);
            } else {
                $this->stack = ['Invalid token:', $match];
                break;
            }
        }

        $this->clear_stack();
    }

    /**
     * @param int $numeral Numeral to add to the RPN stack
     */
    protected function match_numeral($numeral) {
        $this->rpn[]   = $numeral;
        $this->infix[] = $numeral;
    }

    protected function match_dice($dice) {
        $dice          = new CalcDice($dice);
        $this->rpn[]   = $dice->value();
        $this->infix[] = $dice;
    }

    protected function match_set($set) {
        $this->rpn[]   = new CalcSet($set);
        $this->infix[] = end($this->rpn);
    }

    /**
     * @param $variable
     */
    protected function match_variable($variable) {
        $this->rpn[]   = $variable;
        $this->infix[] = end($this->rpn);
    }

    protected function match_parens($parenthesis) {
        $this->infix[] = $parenthesis;
        if ($parenthesis == '(') {
            $this->stack[] = $parenthesis;
        } else {
            while (count($this->stack) > 0 && end($this->stack) != '(') {
                $this->rpn[] = array_pop($this->stack);
            }
            array_pop($this->stack);
        }
    }

    /**
     * @param $operator
     */
    protected function match_operator($operator) {
        while (
            count($this->stack) > 0
            &&
            end($this->stack) != '('
            &&
            $this->ooo[$operator] <= $this->ooo[end($this->stack)]
        ) {
            $this->rpn[] = array_pop($this->stack);
        }
        $this->stack[] = $operator;
        $this->infix[] = $operator;
    }

    protected function clear_stack() {
        while (count($this->stack) > 0) {
            $this->rpn[] = array_pop($this->stack);
        }
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function __invoke() {

        $stack = [];

        foreach ($this->rpn as $step) {
            if (is_object($step) || !isset($this->ooo[$step])) {
                $stack[] = $step;
            } else {
                $r1 = array_pop($stack);
                $r2 = array_pop($stack);

                if (is_numeric($r1) && is_numeric($r2)) {
                    $stack[] = CalcOperation::calc($step, $r2, $r1);
                }
                if ($r1 instanceof CalcSet && is_numeric($r2)) {
                    $stack[] = $r1->calc($step, $r2);
                }
                if (is_numeric($r1) && $r2 instanceof CalcSet) {
                    $stack[] = $r2->rcalc($step, $r1);
                }
                if ($r1 instanceof CalcSet && $r2 instanceof CalcSet) {
                    $stack[] = $r1->mcalc($step, $r2);
                }
            }
        }

        if (count($stack) > 1) {
            throw new \Exception('Missing operator near "' . $stack[1] . '".');
        } else {
            $out = reset($stack);

            return $out;
        }
    }

    public function infix() {
        return implode(' ', $this->infix);
    }

}
