  <h3>Some temp Testing:</h3>
  <p>include path: <?php echo get_include_path() ?></p>
<?php
$a = 0;
$b = $a / 25;
echo "b = " . $b . "<br/>";
echo "b round down = " . round($b) . "<br/>";
echo "b ceil = " . ceil($b) . "<br/>";

$row = array();
$row[] = 'abc';
$row[] = 'bcd';

var_dump($row);
?>
