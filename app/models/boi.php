<?php
require_once 'Conexao.php';

class Boi
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conectar();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function salvar($dados)
    {
        $sql = "INSERT INTO animal 
        (brinco_identificador, raca_id, lote_id, data_nascimento, sexo, peso_entrada)
        VALUES (:brinco, :raca, :lote, :nascimento, :sexo, :peso)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($dados);
    }

    public function listarTodos()
    {
        return $this->pdo
            ->query("SELECT * FROM animal ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}