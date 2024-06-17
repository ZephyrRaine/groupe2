<?php
try {
    $dbh = new PDO('mysql:host=10.96.16.90;port=3306;dbname=groupe2', 'groupe2', 'groupe2');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['dbh'] = $dbh;
} catch (Exception $exception) {
    die('Erreur : ' . $exception->getMessage());
}
?>
