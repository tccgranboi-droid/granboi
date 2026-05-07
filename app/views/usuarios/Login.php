<h2>Login</h2> 
<form method="POST" action="<?= BASE_URL ?>usuario/login_usuario">  <input type="email" name="email" placeholder="E-mail" autofocus><br><br>  <input type="password" name="senha" placeholder="Senha"><br><br>
 <button type="submit">Entrar</button> 
</form> 
<!--rotinas que recebem as mensagens de erro de login: --> 
<?php if(isset($_SESSION['erro'])): ?> 
<div id="msg-sucesso" class="msg"> 
<?PHP ECHO $_SESSION['erro']; ?> 
</div> 
<?php unset($_SESSION['erro']); ?> 
<?php endif; ?> 
<?php 
// usando javascript para a mensagem durar 2 segundos e sumir: 
?> 
<script> 
setTimeout(function()  
{ 
 const msg = document.querySelector(".msg"); 
 if (msg) { 
 msg.style.opacity = "0"; 
 setTimeout(() => msg.remove(), 500); 
 } 
}, 2000); 
</script> 
