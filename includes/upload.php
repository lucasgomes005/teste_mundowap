<?php

require '../vendor/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$db = new MysqliDb(Array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'mundowap')
);

$arquivo = $_FILES['fileUpload'];
$pasta = '../upload/';
$caminho = $pasta . $arquivo['name'];
move_uploaded_file($arquivo['tmp_name'], $caminho);

$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
$filePath = $caminho;
$reader->open($filePath);

$count = 0; $i = 0;
$dados = array();

foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $row) {
        foreach ($row as $valor) {
            if ($valor != '') {
                if (gettype($valor) == 'object') {
                    $valor = $valor->format('Y-m-d');
                }
                $u[] = $valor;
            }
        }
        $dados[$count] = implode(',', $u);
        if($count == 0 && $dados[0] != 'EAN,NOME PRODUTO,PREÇO,ESTOQUE,DATA FABRICAÇÃO'){
            $reader->close();
            unlink($caminho);
            header('location: ../tabela.php?msg=cabecalho');            
        }
        $u = array();
        if ($dados[$count] != '') {
            $count++;
        }
    }
}
 
unset($dados[count($dados) - 1]);
unset($dados[0]);

foreach ($dados as $dado) {
    
    $id = '';
    $dado = explode(',', $dado);
    $db->where('ean', $dado[0]);
    $results = $db->get('produtos');

    if (!$results) {
        if (isset($dado[4])) {
            $insert = Array(
                'ean' => $dado[0],
                'nome' => $dado[1],
                'preco' => $dado[2],
                'estoque' => $dado[3],
                'data_fabricacao' => $dado[4]
            );
        } else {
            $insert = Array(
                'ean' => $dado[0],
                'nome' => $dado[1],
                'preco' => $dado[2],
                'estoque' => $dado[3]
            );
        }
        
        $id = $db->insert('produtos', $insert);
    }
    
    header('location: ../tabela.php?msg=aprovado');  
}

$reader->close();
unlink($caminho);
?>