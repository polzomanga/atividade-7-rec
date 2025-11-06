# Biblioteca CRUD - PHP + XAMPP

## Sobre o projeto
CRUD completo para gerenciar autores, livros, leitores e empréstimos de uma biblioteca.

## Como rodar no XAMPP
1. Importe o arquivo `db/db.sql` pelo phpMyAdmin para criar e popular o banco.
2. Configure o arquivo `config/db.php` com os dados do seu MySQL.
3. Coloque o projeto na pasta `htdocs` do XAMPP.
4. Inicie o Apache e o MySQL pelo painel do XAMPP.

## Estrutura do banco
- autores: id_autor, nome, nacionalidade, ano_nascimento
- livros: id_livro, titulo, genero, ano_publicacao, id_autor (FK)
- leitores: id_leitor, nome, email, telefone
- emprestimos: id_emprestimo, id_livro (FK), id_leitor (FK), data_emprestimo, data_devolucao

## Como configurar a conexão
Edite `config/db.php` com seu usuário e senha do MySQL.

## Como executar os scripts
Acesse pelo navegador: `http://localhost/atividade-7-rec/`

## Como testar cada CRUD
Cada entidade terá páginas para criar, listar, editar e excluir registros. Siga as instruções nas telas para testar cada funcionalidade.

## Observações
- O projeto não utiliza frameworks, apenas PHP puro.
- CSS simples incluso.
- Regras de negócio implementadas conforme solicitado.
