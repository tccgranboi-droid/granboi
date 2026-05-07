<?php

class Conexao {
public static function conectar()
{
$host = 'localhost';
$dbname = 'granboi_db';
$usuario = 'root';
$senha = '';
try
{

// Cria uma nova conexão PDO
return new PDO("mysql:host=$host;dbname=$dbname", $usuario, $senha);
} catch (PDOException $e) {
// Se der erro, exibe a mensagem 
die("Erro ao conectar: " . $e->getMessage());
}
}
}
?>