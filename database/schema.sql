-- Banco de dados: amopet_usuarios

-- --------------------------------------------------------

-- Estrutura da tabela logins
CREATE TABLE logins (
    id INT(11) NOT NULL AUTO_INCREMENT, -- ID único para cada login
    email VARCHAR(255) NOT NULL,         -- Email do usuário
    senha VARCHAR(255) NOT NULL,         -- Senha do usuário
    PRIMARY KEY (id)                     -- Define a coluna 'id' como chave primária
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estrutura da tabela usuarios
CREATE TABLE usuarios (
    id INT(11) NOT NULL AUTO_INCREMENT,  -- ID único para cada usuário
    email VARCHAR(255) NOT NULL,         -- Email do usuário
    nome_usuario VARCHAR(100) NOT NULL,  -- Nome de usuário
    senha VARCHAR(255) NOT NULL,         -- Senha do usuário
    data_nascimento DATE NOT NULL,       -- Data de nascimento
    genero ENUM('masculino', 'feminino', 'outro') NOT NULL, -- Gênero do usuário
    PRIMARY KEY (id)                     -- Define a coluna 'id' como chave primária
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
