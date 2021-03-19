
-- Visão saldo pessoa
CREATE VIEW visao_saldo_pessoa AS
    SELECT 
        id, nome, endereco, bairro, id_telefone, email
        (SELECT sum(valor) FROM conta_areceber WHERE id_cliente=pessoa.id) AS total,
        (SELECT sum(valor) FROM conta_areceber WHERE id_cliente=pessoa.id AND situacao='N') AS aberto
    FROM pessoa 
    ORDER BY nome DESC;
--
SELECT id, nome, endereco, bairro, id_telefone, email FROM pessoa ORDER BY nome DESC;