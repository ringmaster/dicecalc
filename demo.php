<?php

require 'src/DiceCalc/Calc.php';
require 'src/DiceCalc/CalcSet.php';
require 'src/DiceCalc/CalcDice.php';
require 'src/DiceCalc/CalcOperation.php';
require 'src/DiceCalc/Random.php';

use DiceCalc\Calc as Calc;

$expression = isset($_GET['expression']) ? $_GET['expression'] : '';

$calc = new Calc( $expression );


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Calc Test</title>
</head>
<body>

<form>
	<input type="text" name="expression" value="<?php echo htmlspecialchars( $expression ); ?>">
	<input type="submit" value="Calc">
</form>
<style type="text/css">
	s {
		color: #999;
	}

	.true {
		color: green;
		font-weight: bold;
	}

	.false {
		color: #999;
	}
</style>


<pre>
  <?php

  echo htmlspecialchars( $expression ) . "\n";
  echo $calc->infix();
  echo " => ";
  echo $calc() . "\n";

  ?>
</pre>

</body>
</html>
