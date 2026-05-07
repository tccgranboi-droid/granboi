<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    
</body>
</html>
<div class="container">
    <h2>Cadastro de Usuário</h2>
    <!-- Mensagem de erro (vem do controller via $_SESSION) -->
    <?php if (isset($_SESSION['erro'])): ?>
        <div id="msg-erro" class="msg msg-erro">
            <?= $_SESSION['erro']; ?>
        </div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>
    <!-- Mensagem de sucesso -->
    <?php if (isset($_SESSION['sucesso'])): ?>
        <div id="msg-sucesso" class="msg msg-sucesso">
            <?= $_SESSION['sucesso']; ?>
        </div>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>usuario/cadastrar">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" maxlength="150"
               autofocus required>
        <label for="nome_social">Nome Social (opcional):</label>
        <input type="text" id="nome_social" name="nome_social" maxlength="50">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" maxlength="14"
               placeholder="000.000.000-00" required>
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" maxlength="15"
               placeholder="(00) 00000-0000" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="50" required>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" maxlength="50" required>
        <label for="senhac">Confirmar Senha:</label>
        <input type="password" id="senhac" name="senhac" maxlength="50" required>
        <label for="papel">Função no Sistema:</label>
        <select id="papel" name="papel" required>
            <option value="">Selecione...</option>
            <option value="administrador">Administrador</option>
            <option value="gestor">Gestor</option>
            <option value="veterinario">Veterinário</option>
            <option value="operador">Operador</option>
        </select>
        <!-- Campo que aparece só quando um perfil é selecionado -->
        <div id="campo-senha-perfil" style="display: none;">
            <label for="senha_perfil">Senha do Perfil:</label>
            <input type="password" id="senha_perfil" name="senha_perfil" maxlength="50"
                   placeholder="Digite a senha de acesso do perfil">
            <small>Solicite a senha do seu perfil ao administrador do sistema.</small>
        </div>
        <br>
        <input type="submit" value="Cadastrar" class="btn">
    </form>
</div>
<!-- Faz a mensagem desaparecer após 3 segundos -->
<script>
setTimeout(function() {
    const msg = document.querySelector(".msg");
    if (msg) {
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 3000);
</script>
<!-- Mostra/esconde o campo de senha do perfil conforme a seleção -->
<script>
document.getElementById('papel').addEventListener('change', function() {
    const campoPerfil = document.getElementById('campo-senha-perfil');
    const inputPerfil = document.getElementById('senha_perfil');
    if (this.value !== '') {
        // Mostra o campo e torna obrigatório
        campoPerfil.style.display = 'block';
        inputPerfil.required = true;
    } else {
        // Esconde o campo e remove obrigatoriedade
        campoPerfil.style.display = 'none';
        inputPerfil.required = false;
        inputPerfil.value = '';
    }
});
</script>
