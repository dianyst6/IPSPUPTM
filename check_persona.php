<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
$r = mysqli_query($conn, 'DESCRIBE persona');
while($f = mysqli_fetch_assoc($r)) {
    print_r($f);
}
?>
