<?php

require __DIR__ . '/vendor/autoload.php';

$db = new MysqliDb(Array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'mundowap')
);

$email = $_POST['email'];
$senha = $_POST['senha'];

echo $email.$senha;

$where = $db->where('email',$email);
$where = $db->where('senha',md5($senha));
$results = $db->get('usuarios');

if($results){
    header('location: tabela.php');
    session_start();
    $_SESSION['auth'] = 1;
}else{
    header('location: index.php');
}

?>
