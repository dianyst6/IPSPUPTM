
<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
$r = mysqli_query($conn, 'DESCRIBE afiliados');
while($f = mysqli_fetch_assoc($r)) {
    print_r($f);
}
?>
