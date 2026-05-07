<?php
define('BASE_URL', 'http://localhost/TCC/Granboi/public/');
// Senhas de acesso por perfil
// Cada papel tem uma senha que o usuário precisa informar no cadastro
// para provar que foi autorizado a usar aquele perfil.
// Você pode alterar essas senhas a qualquer momento aqui.
define('SENHAS_PERFIL', [
    'administrador' => 'admin2026',
    'gestor'        => 'gestor2026',
    'veterinario'   => 'vet2026',
    'operador'      => 'oper2026',
]);
?>