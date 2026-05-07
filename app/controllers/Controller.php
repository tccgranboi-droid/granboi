<?php
class Controller
{
    protected function render($view, $dados = [])
    {
        extract($dados);
        require_once __DIR__ . "/../views/layout/cabecalho.php";
        require_once __DIR__ . "/../views/$view.php";
        require_once __DIR__ . "/../views/layout/rodape.php";
    }

    // VERIFICA SE USUÁRIO TEM PAPEL
    protected function tem_papel($papel)
    {
        return isset($_SESSION['meus_papeis']) && in_array($papel, $_SESSION['meus_papeis']);
    }

    //  PROTEGE PÁGINAS
   protected function exigir_papel($papel)
{
    if (!isset($_SESSION['logado'])) {
        header("Location: " . BASE_URL . "usuario/login");
        exit;
    }

    if (!in_array($papel, $_SESSION['meus_papeis'] ?? [])) {
        echo "Acesso negado.";
        exit;
    }
}
}