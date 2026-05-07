<h2>Gados Cadastrados</h2>

<?php if(empty($animais)): ?>
    <p>Nenhum animal cadastrado.</p>
<?php else: ?>

<table border="1" width="100%">
    <tr>
        <th>Brinco</th>
        <th>Sexo</th>
        <th>Nascimento</th>
        <th>Peso</th>
        <th>Status</th>
    </tr>

    <?php foreach($animais as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['brinco_identificador']) ?></td>
            <td><?= htmlspecialchars($a['sexo']) ?></td>
            <td><?= htmlspecialchars($a['data_nascimento']) ?></td>
            <td><?= htmlspecialchars($a['peso_entrada']) ?> kg</td>
            <td><?= htmlspecialchars($a['status']) ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<?php endif; ?>