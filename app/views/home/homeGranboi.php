<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Sistema</title>
</head>
<body>


  <div class="container">

    <!-- Boas-vindas -->
    <div class="welcome">
      <h1>Olá, seja bem-vindo!</h1>
      <p>Hoje é <strong id="current-date"></strong></p>
    </div>
      <br>
      <br>
      <br>
      <br>
      <br>
    <!-- Cards de Estatísticas -->
    <div class="stats">
      <div class="stat-card">
        <div class="stat-number" id="total-cadastros">0</div>
        <div class="stat-label">Total de Cadastros</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="total-ativos">0</div>
        <div class="stat-label">Ativos</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="pendentes">0</div>
        <div class="stat-label">Pendentes</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="hoje">0</div>
        <div class="stat-label">Inativos</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="hoje">0</div>
        <div class="stat-label">Cadastrados Hoje</div>
      </div>
    </div>

    

  <script>
    // Atualiza a data atual
    function atualizarData() {
      const data = new Date();
      const opcoes = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('current-date').textContent = data.toLocaleDateString('pt-BR', opcoes);
    }

    // Inicialização
    window.onload = function() {
      atualizarData();
      carregarTabela();
    };

    // Animação simples nas estatísticas (opcional)
    setTimeout(() => {
      document.querySelectorAll('.stat-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
          card.style.transition = 'all 0.6s ease';
          card.style.transitionDelay = (index * 100) + 'ms';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100);
      });
    }, 300);
  </script>
</body>
</html>