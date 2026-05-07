<?php
$id   = htmlspecialchars($dados['usuario']['id']);
$nome = htmlspecialchars($dados['pessoa']["nome_completo"]);
$num  = htmlspecialchars($dados['pessoa']["telefone_movel"]);
$cpf  = htmlspecialchars($dados['pessoa']["cpf"]);
$email = htmlspecialchars($dados['usuario']["email"]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
</head>
<body>

<h2>Editar Dados</h2>

<form method="POST" action="">
    
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= $nome ?>" required><br><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone" value="<?= $num ?>"><br><br>

    <label>CPF:</label><br>
    <input type="text" name="cpf" value="<?= $cpf ?>" readonly><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $email ?>" required><br><br>

    <button type="submit" name="salvar">Salvar Alterações</button>

</form>

</body>
</html>