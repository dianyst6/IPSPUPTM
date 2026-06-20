<?php
$conn = mysqli_connect('localhost', 'root', '', 'ipsp');
if (!$conn) die('Error: ' . mysqli_connect_error());
$res = mysqli_query($conn, 'DESCRIBE contrato_plan');
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . ' - ' . $row['Type'] . PHP_EOL;
}
?>
