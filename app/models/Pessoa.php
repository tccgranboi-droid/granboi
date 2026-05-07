<?php
require_once 'Conexao.php';

// Classe com métodos para cadastro e gerenciamento de usuários
// Trabalha com 3 tabelas: pessoa, usuario e usuario_papel
class Pessoa {
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conectar();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // ========================================================
    // CADASTRAR NOVO USUÁRIO
    // ========================================================

    public function cadastrar($dados)
    {
        // Pegamos os dados que vieram do formulário ($_POST)
        $nome_completo = trim($dados['nome'] ?? '');
        $nome_social   = trim($dados['nome_social'] ?? '');
        $cpf           = trim($dados['cpf'] ?? '');
        $telefone      = trim($dados['telefone'] ?? '');
        $email         = trim($dados['email'] ?? '');
        $senhaOriginal = $dados['senha'] ?? '';
        $papel         = $dados['papel'] ?? '';

        // Validação básica: nome, email e senha são obrigatórios
        if (empty($nome_completo) || empty($email) || empty($senhaOriginal)) {
            return false;
        }

        // Criptografa a senha com bcrypt (nunca salvar senha em texto puro!)
        $senhaHash = password_hash($senhaOriginal, PASSWORD_DEFAULT);

        // Verifica se já existe um usuário com esse email
        $cmd = $this->pdo->prepare("SELECT id FROM usuario WHERE email = :e");
        $cmd->bindValue(":e", $email);
        $cmd->execute();
        if ($cmd->rowCount() > 0) {
            return "duplicado";
        }

        // Verifica se já existe uma pessoa com esse CPF (se CPF foi informado)
        if (!empty($cpf)) {
            $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE cpf = :c");
            $cmd->bindValue(":c", $cpf);
            $cmd->execute();
            if ($cmd->rowCount() > 0) {
                return "duplicado";
            }
        }

        try {
            // ---- PASSO 1: Inserir na tabela PESSOA ----
            // A tabela pessoa tem: id, nome_completo, nome_social, cpf, telefone_movel, email

            $cmd = $this->pdo->prepare("
                INSERT INTO pessoa
                    (nome_completo, nome_social, cpf, telefone_movel, email)
                VALUES
                    (:nome, :social, :cpf, :tel, :email)
            ");
            $cmd->execute([
                ':nome'   => $nome_completo,
                ':social' => !empty($nome_social) ? $nome_social : null,
                ':cpf'    => !empty($cpf) ? $cpf : null,
                ':tel'    => !empty($telefone) ? $telefone : null,
                ':email'  => $email
            ]);

            // Pega o ID da pessoa que acabou de ser inserida
            $id_pessoa = $this->pdo->lastInsertId();

            // ---- PASSO 2: Inserir na tabela USUARIO ----
            // A tabela usuario tem: id, nome, email, senha, pessoa_id
            // IMPORTANTE: a relação é usuario.pessoa_id → pessoa.id
            // (O código antigo fazia ao contrário: pessoa.usuario_id — isso não existe mais)
            $cmd2 = $this->pdo->prepare("
                INSERT INTO usuario
                    (nome, email, senha, pessoa_id)
                VALUES
                    (:nome, :email, :senha, :pessoa_id)
            ");
            $cmd2->execute([
                ':nome'      => $nome_completo,
                ':email'     => $email,
                ':senha'     => $senhaHash,
                ':pessoa_id' => $id_pessoa
            ]);

            // Pega o ID do usuário que acabou de ser inserido
            $id_usuario = $this->pdo->lastInsertId();

            // ---- PASSO 3: Inserir na tabela USUARIO_PAPEL ----
            // Associa o usuário ao papel (administrador, gestor, veterinario, operador)
            // Os IDs dos papéis vêm do INSERT inicial no banco:
            //   1 = administrador, 2 = gestor, 3 = veterinario, 4 = operador
            $id_papel = 0;
     if ($papel == "admin")        $id_papel = 1;
if ($papel == "gestor")       $id_papel = 2;
if ($papel == "veterinario")  $id_papel = 3;
if ($papel == "operador")     $id_papel = 4;

            if ($id_papel > 0) {
                $cmd3 = $this->pdo->prepare("
                    INSERT INTO usuario_papel
                        (usuario_id, papel_id)
                    VALUES
                        (:uid, :pid)
                ");
                $cmd3->execute([
                    ':uid' => $id_usuario,
                    ':pid' => $id_papel
                ]);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // ========================================================
    // PESQUISAR USUÁRIO PARA EDIÇÃO
    // ========================================================
    // Busca dados do usuário + pessoa para preencher o formulário de edição
    public function pesquisarUsuario($id)
    {
        // Busca na tabela usuario
        $cmd = $this->pdo->prepare("SELECT * FROM usuario WHERE id = :i");
        $cmd->bindValue(":i", $id, PDO::PARAM_INT);
        $cmd->execute();
        $dadosUsuario = $cmd->fetch(PDO::FETCH_ASSOC);

        // Busca na tabela pessoa usando o pessoa_id do usuario
        // ANTES (errado): WHERE usuario_id = :i (essa coluna não existe no banco novo)
        // AGORA (correto): WHERE id = pessoa_id (que está na tabela usuario)
        $cmd2 = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :i");
        $cmd2->bindValue(":i", $dadosUsuario['pessoa_id'], PDO::PARAM_INT);
        $cmd2->execute();
        $dadosPessoa = $cmd2->fetch(PDO::FETCH_ASSOC);

        return [
            'usuario' => $dadosUsuario,
            'pessoa'  => $dadosPessoa
        ];
    }

    // ========================================================
    // ATUALIZAR DADOS DO USUÁRIO
    // ========================================================
    public function atualizarUsuario($dados)
    {
        $id            = isset($dados['id']) ? (int)$dados['id'] : 0;
        $nome_completo = trim($dados['nome'] ?? '');
        $nome_social   = trim($dados['nome_social'] ?? '');
        $cpf           = trim($dados['cpf'] ?? '');
        $telefone      = trim($dados['telefone'] ?? '');
        $email         = trim($dados['email'] ?? '');
        $senha         = $dados['senha'] ?? '';

        // Se digitou nova senha, criptografa. Senão, mantém a atual do banco.
        if (!empty($senha)) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        } else {
            $cmdSenha = $this->pdo->prepare("SELECT senha FROM usuario WHERE id = :i");
            $cmdSenha->bindValue(":i", $id, PDO::PARAM_INT);
            $cmdSenha->execute();
            $senhaHash = $cmdSenha->fetchColumn();
        }

        // Atualiza tabela USUARIO (nome, email, senha)
        $cmd = $this->pdo->prepare("
            UPDATE usuario
            SET nome = :nome, email = :email, senha = :senha
            WHERE id = :i
        ");
        $cmd->execute([
            ':nome'  => $nome_completo,
            ':email' => $email,
            ':senha' => $senhaHash,
            ':i'     => $id
        ]);

        // Busca o pessoa_id para saber qual registro de pessoa atualizar
        $cmdPessoa = $this->pdo->prepare("SELECT pessoa_id FROM usuario WHERE id = :i");
        $cmdPessoa->bindValue(":i", $id, PDO::PARAM_INT);
        $cmdPessoa->execute();
        $pessoa_id = $cmdPessoa->fetchColumn();

        // Atualiza tabela PESSOA (só os campos que existem no schema novo)
        // Sem endereço, sem foto, sem sexo, sem data_nascimento
        $cmd2 = $this->pdo->prepare("
            UPDATE pessoa
            SET nome_completo = :nome,
                nome_social = :social,
                cpf = :cpf,
                telefone_movel = :tel,
                email = :email
            WHERE id = :i
        ");
        $cmd2->execute([
            ':nome'   => $nome_completo,
            ':social' => !empty($nome_social) ? $nome_social : null,
            ':cpf'    => $cpf,
            ':tel'    => $telefone,
            ':email'  => $email,
            ':i'      => $pessoa_id
        ]);

        return true;
    }

    // ========================================================
    // EXCLUIR USUÁRIO
    // ========================================================
    // ORDEM CORRETA de exclusão (por causa das foreign keys):
    //   1) usuario_papel (depende de usuario)
    //   2) usuario (depende de pessoa)
    //   3) pessoa (tabela base)
    // O código antigo deletava usuario primeiro, o que daria erro de FK!
    public function excluirUsuario($id)
    {
        // Primeiro, busca o pessoa_id antes de deletar o usuario
        $cmd = $this->pdo->prepare("SELECT pessoa_id FROM usuario WHERE id = :i");
        $cmd->bindValue(":i", $id, PDO::PARAM_INT);
        $cmd->execute();
        $pessoa_id = $cmd->fetchColumn();

        // 1) Deleta da tabela usuario_papel (remove a associação de papel)
        $cmd1 = $this->pdo->prepare("DELETE FROM usuario_papel WHERE usuario_id = :i");
        $cmd1->bindValue(":i", $id);
        $cmd1->execute();

        // 2) Deleta da tabela usuario (remove login/senha)
        $cmd2 = $this->pdo->prepare("DELETE FROM usuario WHERE id = :i");
        $cmd2->bindValue(":i", $id);
        $cmd2->execute();

        // 3) Deleta da tabela pessoa (remove dados pessoais)
        if ($pessoa_id) {
            $cmd3 = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :i");
            $cmd3->bindValue(":i", $pessoa_id);
            $cmd3->execute();
        }

        return true;
    }

    // Validação de CPF brasileiro (11 dígitos, cálculo dos dígitos verificadores)
    private function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
