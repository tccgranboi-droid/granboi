<?php
require_once 'Conexao.php';
// Classe responsável pelo LOGIN do usuário
// Busca dados em 3 tabelas: usuario, usuario_papel e pessoa
class Usuario {
    private $pdo;
    public function __construct()
    {
        $this->pdo = Conexao::conectar();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
   public function logarUsuario($em, $sen)
{
    // Busca usuário
    $cmd = $this->pdo->prepare("SELECT * FROM usuario WHERE email = :e");
    $cmd->bindValue(":e", $em);
    $cmd->execute();

    if ($cmd->rowCount() == 0) {
        return "email_inexistente";
    }

    $dados = $cmd->fetch(PDO::FETCH_ASSOC);
    $id_usu = $dados['id'];

// Busca papéis do usuário
    $stmt = $this->pdo->prepare("
        SELECT p.nome 
        FROM usuario_papel up
        JOIN papel p ON p.id = up.papel_id
        WHERE up.usuario_id = :i
    ");
    $stmt->bindValue(":i", $id_usu);
    $stmt->execute();

    $papeis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Busca pessoa
    $stmt2 = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :i");
    $stmt2->bindValue(":i", $dados['pessoa_id']);
    $stmt2->execute();
    $dados3 = $stmt2->fetch(PDO::FETCH_ASSOC);

    // Verifica senha
if (password_verify($sen, $dados['senha'])) {

        $listaPapeis = [];

        foreach ($papeis as $p) {
            $listaPapeis[] = $p['nome'];
        }

        return [
            'usuario' => $dados,
            'papeis'  => $listaPapeis,
            'pessoa'  => $dados3
        ];

    } else {
        return "senha_nao_confere";
    }
}
}
