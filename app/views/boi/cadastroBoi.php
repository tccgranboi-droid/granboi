<div class="container">

  <div class="card">
    <div class="card-header">Cadastrar Animal</div>

    <form method="POST" action="<?= BASE_URL ?>boi/salvar">

      <div class="form-grid">

        <div>
          <label>Brinco / Identificação</label>
          <input type="text" name="brinco" required>
        </div>

        <div>
          <label>Sexo</label>
          <select name="sexo" required>
            <option value="">Selecione...</option>
            <option value="M">Macho</option>
            <option value="F">Fêmea</option>
          </select>
        </div>

        <div>
          <label>Raça (ID)</label>
          <input type="number" name="raca_id">
        </div>

        <div>
          <label>Lote (ID)</label>
          <input type="number" name="lote_id">
        </div>

        <div>
          <label>Data de Nascimento</label>
          <input type="date" name="data_nascimento">
        </div>

        <div>
          <label>Peso Inicial (kg)</label>
          <input type="number" name="peso_entrada" step="0.01">
        </div>

      </div>

      <div style="padding: 0 1.5rem 1.5rem;">
        <button type="submit" class="btn-primary">Cadastrar Animal</button>
      </div>

    </form>

  </div>

</div>