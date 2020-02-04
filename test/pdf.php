<?php 

require_once "../Cups.php";

$printer = new Cups\Printer;

$printer->submit( 'sample.pdf' );
$printer->defaultSubmit( 'sample.pdf', 2);

?>
