<?php
require_once "Controller.php";
class UsuarioController extends Controller
{
    // Exibe o formulário de cadastro
    public function cadastro()
    {
        $this->render("usuarios/Cadastro");
    }
    // Recebe os dados do formulário de cadastro e processa
    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar se as senhas coincidem
            if ($_POST['senha'] !== $_POST['senhac']) {
                $_SESSION['erro'] = "As senhas não coincidem.";
                header("Location: " . BASE_URL . "usuario/cadastro");
                exit;
            }
            // Validar a senha do perfil selecionado
            // Cada papel tem uma senha definida em config.php (SENHAS_PERFIL)
            $papelEscolhido = $_POST['papel'] ?? '';
            $senhaPerfil    = $_POST['senha_perfil'] ?? '';
            if (!empty($papelEscolhido) && isset(SENHAS_PERFIL[$papelEscolhido])) {
                if ($senhaPerfil !== SENHAS_PERFIL[$papelEscolhido]) {
                    $_SESSION['erro'] = "Senha do perfil incorreta para " . ucfirst($papelEscolhido) . ".";
                    header("Location: " . BASE_URL . "usuario/cadastro");
                    exit;
                }
            }
            // Limpa CPF e telefone (remove pontos, traços, parênteses, espaços)
            // Deixa só os números para salvar no banco
            $_POST['cpf'] = $this->limparNumero($_POST['cpf']);
            $_POST['telefone'] = $this->limparNumero($_POST['telefone']);
            require_once "../app/models/Pessoa.php";
            $pessoa = new Pessoa();
            $resultado = $pessoa->cadastrar($_POST);
            if ($resultado === true) {
                $_SESSION['sucesso'] = "Usuário cadastrado com sucesso!";
                header("Location: " . BASE_URL . "usuario/login");
            } elseif ($resultado === "duplicado") {
                $_SESSION['erro'] = "E-mail ou CPF já cadastrado.";
                header("Location: " . BASE_URL . "usuario/cadastro");
            } else {
                $_SESSION['erro'] = "Erro ao cadastrar.";
                header("Location: " . BASE_URL . "usuario/cadastro");
            }
            exit;
        }
    }
    // Remove tudo que não for dígito (0-9) de uma string
    private function limparNumero($valor)
    {
        return preg_replace('/\D/', '', $valor);
    }
    // Validação de CPF brasileiro
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
    // Exibe o formulário de login
    public function login()
    {
        $this->render("usuarios/login");
    }
    // Processa o login do usuário
    public function login_usuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once "../app/models/Usuario.php";
            $usuario = new Usuario();
            $resultado = $usuario->logarUsuario(
                $_POST['email'],
                $_POST['senha']
            );
            // Se retornou array, login deu certo
            if (is_array($resultado)) {
                $_SESSION['logado'] = "SIM";
                $_SESSION['id_usuario'] = $resultado['usuario']['id'];
                $_SESSION['nome_usuario'] = $resultado['pessoa']['nome_completo'];
                $_SESSION['email_usu'] = $resultado['usuario']['email'];
       $_SESSION['meus_papeis'] = $resultado['papeis'];
                header("Location: " . BASE_URL . "home/homeGranboi");
            } elseif ($resultado === "email_inexistente") {
                $_SESSION['erro'] = "E-mail não encontrado.";
                header("Location: " . BASE_URL . "usuario/login");
            } elseif ($resultado === "senha_nao_confere") {
                $_SESSION['erro'] = "Senha incorreta.";
                header("Location: " . BASE_URL . "usuario/login");
            }
            exit;
        }
    }
    // Exibe formulário de edição dos dados do usuário logado
    public function editar()
    {
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] != 'SIM') {
            header("Location: " . BASE_URL . "usuario/login");
            exit;
        }
        require_once "../app/models/Pessoa.php";
        $pessoa = new Pessoa();
        $dados = $pessoa->pesquisarUsuario($_SESSION['id_usuario']);
        $this->render("usuarios/editar_usuario", $dados);
    }
    // Processa a edição dos dados do usuário
    public function editar_usuario()
    {
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] != 'SIM') {
            header("Location: " . BASE_URL . "usuario/login");
            exit;
        }
        // Limpa CPF e telefone
        $_POST['cpf'] = $this->limparNumero($_POST['cpf']);
        $_POST['telefone'] = $this->limparNumero($_POST['telefone']);
        require_once __DIR__ . "/../models/Pessoa.php";
        $pessoa = new Pessoa();
        $resultado = $pessoa->atualizarUsuario($_POST);
        if ($resultado === true) {
            $_SESSION['sucesso'] = "Alteração feita com sucesso!";
            header("Location: " . BASE_URL . "usuario/editar");
        } else {
            $_SESSION['erro'] = "Falha na alteração!";
            header("Location: " . BASE_URL . "usuario/editar");
        }
        exit;
    }
    // Exclui a conta do usuário logado
    public function excluir()
    {
        if (isset($_SESSION['id_usuario'])) {
            require_once __DIR__ . "/../models/Pessoa.php";
            $pessoa = new Pessoa();
            $id = $_SESSION['id_usuario'];
            if ($pessoa->excluirUsuario($id)) {
                $_SESSION = [];
                session_destroy();
                header("Location: " . BASE_URL . "home/homeGranboi");
                exit;
            } else {
                header("Location: " . BASE_URL . "usuario/editar");
                exit;
            }
        }
    }
    // Faz logout (limpa sessão e redireciona)
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: " . BASE_URL . "home/homeGranboi");
        exit;
    }
}