CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;


CREATE TABLE autores (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nacionalidade VARCHAR(50),
    ano_nascimento INT
);


CREATE TABLE livros (
    id_livro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    genero VARCHAR(50),
    ano_publicacao INT CHECK (ano_publicacao > 1500 AND ano_publicacao <= YEAR(CURDATE())),
    id_autor INT,
    FOREIGN KEY (id_autor) REFERENCES autores(id_autor)
);


CREATE TABLE leitores (
    id_leitor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20)
);


CREATE TABLE emprestimos (
    id_emprestimo INT AUTO_INCREMENT PRIMARY KEY,
    id_livro INT,
    id_leitor INT,
    data_emprestimo DATE NOT NULL,
    data_devolucao DATE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro),
    FOREIGN KEY (id_leitor) REFERENCES leitores(id_leitor),
    CHECK (data_devolucao IS NULL OR data_devolucao >= data_emprestimo)
);


INSERT INTO autores (nome, nacionalidade, ano_nascimento) VALUES
('Machado de Assis', 'Brasileiro', 1839),
('J.K. Rowling', 'Britânica', 1965),
('George Orwell', 'Britânico', 1903);

INSERT INTO livros (titulo, genero, ano_publicacao, id_autor) VALUES
('Dom Casmurro', 'Romance', 1899, 1),
('Harry Potter e a Pedra Filosofal', 'Fantasia', 1997, 2),
('1984', 'Distopia', 1949, 3);

INSERT INTO leitores (nome, email, telefone) VALUES
('Ana Silva', 'ana@email.com', '48999999999'),
('Carlos Souza', 'carlos@email.com', '48988888888');

INSERT INTO emprestimos (id_livro, id_leitor, data_emprestimo, data_devolucao) VALUES
(1, 1, '2025-11-01', NULL),
(2, 2, '2025-10-20', '2025-11-05');
