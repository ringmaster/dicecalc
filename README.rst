========
DiceCalc
========

Project status
--------------

.. image:: https://travis-ci.org/ringmaster/dicecalc.svg?branch=master
	:target: https://travis-ci.org/ringmaster/dicecalc

.. image:: https://scrutinizer-ci.com/g/ringmaster/dicecalc/badges/quality-score.png?b=master
	:target: https://scrutinizer-ci.com/g/ringmaster/dicecalc/?branch=master

How to use
----------
Create a new Calc class, and pass the dice expression to the constructor::
	$calc = new Calc($expression);

Output the interpretation of the roll::
	echo $calc->infix();

Output the result of the roll::
	echo $calc();

Dice Expressions
----------------
The basic expression is to use a "d" followed by the number of sides of the die to roll.  

*Examples:*

d6
	roll a six-sided die
d20
	roll a twenty-sided die
d%
	roll a percentile (1-100) die
df
	roll a fudge (-1, 0, 1) die

To roll multiple of the same sided die, prefix the die type with a multiplier number.

*Examples:*

3d6
	roll d6 three times, sum the result
2d20
	roll d20 twice, sum the result
6df
	roll six fudge dice, sum the result

Basic math operations are possible as expressions.

*Examples:*

4+4
	Add 4+4
4*5
	Multiply 4*5
4+4*5
	Multiply 4*5, then add 4 (order of operations is obeyed)
(4+4)*5
	Add 4+4, then multiply by 5

Math on sets of numbers.

*Examples:*

2*[3,4]
	Multiply 2*3 and 2*4, resulting in a set [6,8]
[1,2]*[3,4]
	Multiply these sets together, resulting in a nested set [[3,4],[6,8]]

Any combination of dice or constants in an expressions.  

*Examples:*

2d6+4
	roll 2d6, sum the dice and add 4
3d8-5
	roll 3d8, sum the dice and subtract 5
2d4+d6
	roll 2d4 and d6, sum all the dice

Evaluate whether a result meets a certain condition or target number.

*Examples:*

4+4 > 7
	returns true, because 4+4 is greater than 7
5d6 > 5
	returns true, because 5d6 is always greater than 5
d20+4 > 12
	simulates a d20 system skill value of 4 compared to a target number of 12

Produce a set of rolls by surrounding the roll with brackets and prefixing with a multiplier.

*Examples:*

6[3d6]
	produces a set of 6 numbers created by rolling 3d6
4[d%]
	produces a set of 4 numbers created by rolling percentile

Roll a custom die by specifying the dice sides in brackets after the "d".

*Examples:*

d[1,2,2,3,3,4]
	Roll a six-sided die with custom numeric faces
d[red,green,blue]
	Roll a three-sided die with colored faces

Keep only the dice in an individual roll that meet specific conditions by applying the "k" postfix.

*Examples:*

5d6 keep > 4
	Role five six-sided dice, keep only the dice that are greater than 4, sum the kept dice
5d6k>4
	same as above
6d8 keep < 4
	Roll six eight-sided dice, keep only the dice that are less than 4, sum the kept dice
6d8k<4
	same as above

Keep only the dice that are the lowest or highest values rolled.

*Examples:*

4d6 highest 3
	Roll 4d6, keep only the highest 3 dice
4d6h3
	same as above
2s20 lowest 1
	Roll 2d20, keep only the lowest die
2d20l1
	same as above

Reroll dice that do not meet certain conditions.

*Examples:*

3d6 reroll < 3
	Roll 3d6, reroll any die that is less than 3
3d6r<3
	same as above
2d% reroll < 40
	Roll 2d%, reroll any die that is less than 40
2d%r<40
	same as above

Our stupid way of rolling D&D character stats.

*Examples:*

4d6r<3h3
	Roll 4d6, reroll any die that is less than 3, keep the highest 3 dice, sum the kept dice

Produce open-ended dice using the "o" prefix.

*Examples:*

4d6o=6
	Roll 4d6.  When any die lands on 6, roll that die again and add the result to that die.  Sum all die totals.

A stupid example nobody would ever use, I hope:

3d6r<4o=6k>6
	Roll 3d6.  Reroll any die less than 4.  When any die is a 6, reroll and add the new value to the original one.  Sum the die totals of only those dice that are greater than 6.

Future Enhancements
-------------------
* Better group handling
* Better custom die handling
* Variable replacements (To handle rolls like: d20 + $str_bonus > $target )
* Range violation exceptions (d6k<0)
* Non-text output method
