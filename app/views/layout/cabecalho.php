<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/index.css">
    <title>GranBoi - Sistema de Gestão</title>
</head>
<body>

<header>
    <h1>GRANBOI</h1>
</header>

<nav>
    <a href="<?= BASE_URL ?>home/homeGranboi">Home</a>

    <!--🔓 NÃO LOGADO -->
    <?php if(!isset($_SESSION['logado'])): ?>
        | <a href="<?= BASE_URL ?>usuario/cadastro">Cadastrar</a>
        | <a href="<?= BASE_URL ?>usuario/login">Login</a>
    <?php endif; ?>

    <!-- LOGADO -->
    <?php if(isset($_SESSION['logado'])): ?>

        | <a href="<?= BASE_URL ?>boi/cadastro">Cadastrar boi</a>
        | <a href="<?= BASE_URL ?>boi/verBoi">Ver bois cadastrados</a>
        | <a href="<?= BASE_URL ?>usuario/editar">Meus Dados</a>

        <?php if (isset($_SESSION['meus_papeis']) && in_array('admin', $_SESSION['meus_papeis'])): ?>
            | <a href="<?= BASE_URL ?>admin/cadastrarFuncionario">Cadastrar Funcionários</a>
        <?php endif; ?>

        | <a href="<?= BASE_URL ?>usuario/logout">Logout</a>

        <?php if (isset($_SESSION['nome_usuario'])): ?>
            <span class="usuario-logado">
                Olá, <?= htmlspecialchars($_SESSION['nome_usuario']) ?>
            </span>
        <?php endif; ?>

    <?php endif; ?>
</nav>

<hr>