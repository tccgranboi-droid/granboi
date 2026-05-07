<?php
require_once "Controller.php";
require_once "../app/models/Boi.php";

class BoiController extends Controller
{
    public function cadastro()
    {
        $this->render("boi/cadastroBoi");
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $model = new Boi();

            $dados = [
                'brinco'     => $_POST['brinco'],
                'raca'       => $_POST['raca_id'],
                'lote'       => $_POST['lote_id'],
                'nascimento' => $_POST['data_nascimento'],
                'sexo'       => $_POST['sexo'],
                'peso'       => $_POST['peso_entrada']
            ];

            $model->salvar($dados);

            header("Location: " . BASE_URL . "boi/verBoi");
            exit;
        }
    }

    public function verBoi()
    {
        $model = new Boi();
        $dados['animais'] = $model->listarTodos();

        $this->render("boi/verBoi", $dados);
    }
}