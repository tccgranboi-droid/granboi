<?php
require_once "Controller.php";

class AdminController extends Controller
{
    public function cadastrarFuncionario()
    {
        $this->exigir_papel('admin');

        $this->render("funcionario/cadastrarFuncionario");
    }

    public function cadastroFuncionario()
    {
        $this->exigir_papel('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            require_once "../app/models/Admin.php";

            $admin = new Admin();
            $admin->setNome($_POST['nome']);
            $admin->setEmail($_POST['email']);
            $admin->setSenha($_POST['senha']);

            $admin->cadastrar();

            $_SESSION['sucesso'] = "Funcionário cadastrado com sucesso!";
            header("Location: " . BASE_URL . "admin/cadastrarFuncionario");
            exit;
        }
    }
}