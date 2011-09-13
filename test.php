<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <title>Calc Test</title>
  </head>
  <body>

<form>
<input type="text" name="expression" value="<?php echo htmlspecialchars($_GET['expression']); ?>">
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

<pre><?php

include 'calc.php';

$expression = $_GET['expression'];

$calc = new Calc($expression);

echo htmlspecialchars($expression) . "\n";
echo $calc->infix();
echo " => ";
echo $calc->calc() . "\n";

?>
</pre>

  </body>
</html>
