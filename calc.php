<?php

define('DICE_REGEX', '(?P<multiple>\d*)d(?P<dietype>\d+|f|\%|\[[^\]]+\])((?P<keep>k(?:eep)?(?P<keepeval>[<>])(?P<keeprange>\d+))|(?P<lowest>l(?:owest)?(?P<lowdice>\d+))|(?P<highest>h(?:ighest)?(?P<highdice>\d+))|(?P<reroll>r(?:eroll)?(?P<rerolleval>[<>])(?P<rerolllimit>\d+))|(?P<openroll>o(?:pen)?(?P<openrolleval>[<>=])(?P<openrolllimit>\d+))|(?P<flags>[z]+))*');

class CalcSet
{
	protected $values = array();
	protected $label;

	function __construct($v)
	{
		if(is_array($v)) {
			$this->values = $v;
			$this->saved_values = $this->values;
			$this->label = '[' . implode(', ', $v) . ']';
		}
		else {
			$this->label = $v;
			preg_match('%^(?P<multiple>\d*)\[(?P<set>[^\]]+)\]$%i', $v, $matches);
			$v = $matches['set'];
			$values = explode(',', $v);
			for($z = 0; $z < max(1,intval($matches['multiple'])); $z++) {
				foreach($values as $v) {
					$this->values[] = $v;
				}
			}
			$this->saved_values = $this->values;
			foreach($this->values as $k => $v) {
				$calc = new Calc($v);
				$this->values[$k] = $calc->calc();
			}
			foreach($this->values as $k => $value) {
				$calc = new Calc($value);
				$this->values[$k] = $calc->calc();
			}
		}
	}
	function __toString()
	{
		$out = array();
		foreach($this->saved_values as $key => $value) {
			$vout = $this->values[$key];
			if(!isset($this->values[$key])) {
				$vout = $this->saved_values[$key];
			}
			if($vout === true) {
				$vout = '<span class="true">true</span>';
			}
			if($vout === false) {
				$vout = '<span class="false">false</span>';
			}
			if(isset($this->values[$key])) {
				$out[] = $vout;
			}
			else {
				$out[] = '<s>' . $vout . '</s>';
			}
		}
		$out = '[' . implode(', ', $out) . ']';
		return $out;
	}
	
	function calc($operator, $operand)
	{
		$out = array();
		foreach($this->values as $value) {
			$out[] = CalcOperation::calc($operator, $value, $operand);
		}
		return new CalcSet($out);
	}

	function rcalc($operator, $operand)
	{
		$out = array();
		foreach($this->values as $value) {
			$out[] = CalcOperation::calc($operator, $operand, $value);
		}
		return new CalcSet($out);
	}
	
	function value()
	{
		$allnumeric = true;
		foreach($this->values as $v) {
			if(is_numeric($v)) {
			}
			else {
				$allnumeric = false;
			}
		}
	
		if($allnumeric) {
			return array_sum($this->values);
		}
		else {
			return $this;
		}
	}
}

class CalcDice extends CalcSet
{
	function __construct($v)
	{
		$this->values = array();
		$this->label = $v;
		$usevalues = array();
		preg_match('/' . DICE_REGEX . '/i', $v, $matches);
		if(intval($matches['multiple']) == 0 && $matches['multiple'] != '0') {
			$matches['multiple'] = 1;
		}
		for($z = 0; $z < $matches['multiple']; $z++) {
			$keep = true;

			$newval = $this->rolltype($matches['dietype']);
			
			if($matches['reroll'] != '') {
				$gtlt = $matches['rerolleval'];
				$range = intval($matches['rerolllimit']);
				if($gtlt == '<' && $newval < $range) {
					$keep = false;
					$z--;
				}
				if($gtlt == '>' && $newval > $range) {
					$keep = false;
					$z--;
				}
			}
			
			if($matches['openroll'] != '') {
				$gtlt = $matches['openrolleval'];
				$range = intval($matches['openrolllimit']);
				$addvals = array($newval);
				$addval = $newval;
				
				if($gtlt == '<') {
					while($addval < $range) {
						$addval = $this->rolltype($matches['dietype']);
						$addvals[] = $addval;
					}
				}
				if($gtlt == '>') {
					while($addval > $range) {
						$addval = $this->rolltype($matches['dietype']);
						$addvals[] = $addval;
					}
				}
				if($gtlt == '=') {
					while($addval == $range) {
						$addval = $this->rolltype($matches['dietype']);
						$addvals[] = $addval;
					}
				}
				$newval = new Calc(implode('+', $addvals));
				$newval = $newval->calc();
			}

			if($keep) {
				$this->values['_' . count($this->values)] = $newval;
			}
		}
		
		$this->saved_values = $this->values;

		if($matches['keep'] != '') {
			$gtlt = $matches['keepeval'];
			$range = intval($matches['keeprange']);
			foreach($this->values as $k => $v) {
				$av = $v instanceof Calc ? $v->calc() : $v;
				if($gtlt == '>' && $av <= $range) {
					unset($this->values[$k]);
				}
				if($gtlt == '<' && $av >= $range) {
					unset($this->values[$k]);
				}
			}
		}

		asort($this->values);
		if(isset($matches['highdice']) && $matches['highdice'] != '') {
			$this->values = array_slice($this->values, -intval($matches['highdice']), null, true);
		}
		if(isset($matches['lowdice']) && $matches['lowdice'] != '') {
			$this->values = array_slice($this->values, 0, intval($matches['lowdice']), true);
		}
	}
	
	function rolltype($dietype) {
		if(is_numeric($dietype)) {
			$newval = rand(1, $dietype);
		}
		elseif($dietype == 'f') {
			$newval = rand(-1, 1);
		}
		elseif($dietype == '%') {
			$newval = rand(1, 100);
		}
		elseif($dietype == '[') {
			$dietype = trim($dietype, '[]');
			$opts = explode(',', $dietype);
			$newval = $opts[rand(0, count($opts)-1)];
		}
		return $newval;
	}
	
	function __toString()
	{
		$out = array();
		foreach($this->saved_values as $key => $value) {
			$vout = $this->saved_values[$key];
			if($vout === true) {
				$vout = '<span class="true">true</span>';
			}
			if($vout === false) {
				$vout = '<span class="false">false</span>';
			}
			if(isset($this->values[$key])) {
				$out[] = $vout;
			}
			else {
				if($vout instanceof Calc) {
					$out[] = '<s>' . $vout->calc() . '</s>';
				}
				else {
					$out[] = '<s>' . $vout . '</s>';
				}
			}
		}
		$_out = '[' . $this->label . ':';
		$comma = '';
		foreach($out as $o) {
			if($o instanceof Calc) {
				$_out .= $comma . $o->calc();
			}
			else {
				$_out .= $comma . $o;
			}
			$comma = ' + ';
		}
		$_out .= ']';
		return $_out;
	}
}

class CalcOperation
{
	function calc($operator, $operand2, $operand1)
	{
		switch($operator) {
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
	
	function reduce($r) {
		if($r instanceof Calc) {
			return $r->calc();
		}
		if(is_numeric($r)) {
			return $r;
		}
		throw Exception('This is not a number');
	}
	
	function add($r1, $r2)
	{
		try{
			return self::reduce($r1) + self::reduce($r2);
		}
		catch(Exception $e) {
			return $r1 . $r2;
		}
	}
	
	function multiply($r1, $r2)
	{
		if(is_numeric($r1) && is_numeric($r2)) {
			return $r1 * $r2;
		}
	}
	
	function subtract($r1, $r2)
	{
		if(is_numeric($r1) && is_numeric($r2)) {
			return $r1 - $r2;
		}
	}
	
	function divide($r1, $r2)
	{
		if(is_numeric($r1) && is_numeric($r2)) {
			return $r1 / $r2;
		}
	}
	
	function exponent($r1, $r2)
	{
		if(is_numeric($r1) && is_numeric($r2)) {
			return pow($r1, $r2);
		}
	}
	
	function greaterthan($r1, $r2)
	{
		try{
			return self::reduce($r1) > self::reduce($r2);
		}
		catch(Exception $e) {
			return $r1 > $r2;
		}
	}
	
	function lessthan($r1, $r2)
	{
		if(is_numeric($r1) && is_numeric($r2)) {
			return ($r1 < $r2);
		}
	}
	
	function equalto($r1, $r2)
	{
		if(is_numeric($r1) && is_numeric($r2)) {
			return ($r1 == $r2);
		}
	}
}

class Calc
{
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

		preg_match_all('%(?:(?P<dice>' . DICE_REGEX . ')|(?P<set>\d*\[[^\]]+\])|(?P<numeral>[\d\.]+)|(?P<operator>[+\-*^><=/])|(?P<variable>\$[a-z_]+)|(?P<parens>[()]))%i', $this->expression, $matches, PREG_SET_ORDER);
		
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
			if(is_bool($out)) {
				return $out ? '<span class="true">true</span>' : '<span class="false">false</span>';
			}
			else {
				return $out;
			}
		}
	}
	
	function infix()
	{
		return implode(' ', $this->infix);
	}
}

?>