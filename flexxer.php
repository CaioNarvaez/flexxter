
<?php 

include 'database_connection.php';
include 'Employee.php';
include 'Machine.php';

$machine = new Machine();
echo $machine->showResources("Sandy", "d0g");

?>
