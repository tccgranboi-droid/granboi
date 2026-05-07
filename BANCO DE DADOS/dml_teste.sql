USE granboi_db;

-- =========================================
-- INSERIR PESSOAS
-- =========================================
INSERT INTO pessoa (nome_completo, nome_social, cpf, telefone_movel, email) VALUES
('Administrador Teste', NULL, '11111111111', '5599999999999', 'admin@teste.com'),
('Veterinario Teste', NULL, '22222222222', '5588888888888', 'vet@teste.com'),
('Operador Teste', NULL, '33333333333', '5577777777777', 'operador@teste.com');

INSERT INTO usuario (nome, email, senha, pessoa_id) VALUES
('admin', 'admin@teste.com', '$2y$10$wH8Qy0z7Yw1XQp7qZz7g2uP6y7lH1Q0zF6mR8b9X0xY1z2a3b4c5d', 1),
('vet', 'vet@teste.com', '$2y$10$wH8Qy0z7Yw1XQp7qZz7g2uP6y7lH1Q0zF6mR8b9X0xY1z2a3b4c5d', 2),
('operador', 'operador@teste.com', '$2y$10$wH8Qy0z7Yw1XQp7qZz7g2uP6y7lH1Q0zF6mR8b9X0xY1z2a3b4c5d', 3);

-- =========================================
-- VINCULAR PAPÉIS (RBAC)
-- =========================================
INSERT INTO usuario_papel (usuario_id, papel_id) VALUES
(1, 1), -- admin
(2, 3), -- veterinario
(3, 4); -- operador

-- =========================================
-- VERIFICAÇÃO
-- =========================================
SELECT u.id, u.nome, u.email, p.nome AS papel
FROM usuario u
JOIN usuario_papel up ON up.usuario_id = u.id
JOIN papel p ON p.id = up.papel_id;

