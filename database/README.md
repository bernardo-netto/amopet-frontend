# Banco de Dados do Projeto Amopet

## Estrutura

Este diretório contém o script SQL para criar as tabelas do banco de dados `amopet_usuarios`.

### Script

- **schema.sql**: Contém a definição das tabelas `logins` e `usuarios`.

## Instruções

1. Crie um banco de dados vazio chamado `amopet_usuarios`.
2. Execute o script `schema.sql` para criar as tabelas.


### Explicação das Tabelas

- **Tabela `logins`**:
  - `id`: ID único do login (chave primária).
  - `email`: Email do usuário.
  - `senha`: Senha do usuário.

- **Tabela `usuarios`**:
  - `id`: ID único do usuário (chave primária).
  - `email`: Email do usuário.
  - `nome_usuario`: Nome de usuário.
  - `senha`: Senha do usuário.
  - `data_nascimento`: Data de nascimento do usuário.
  - `genero`: Gênero do usuário (masculino, feminino ou outro).


