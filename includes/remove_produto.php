<?php

require '../vendor/autoload.php';

$db = new MysqliDb(Array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'mundowap')
);

$ean = $_GET['ean'];
$db->where('ean',$ean);
$db->delete('produtos');
header('location: ../tabela.php');

?>
