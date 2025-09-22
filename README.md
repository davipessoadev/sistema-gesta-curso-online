# Sistema de Gestão de Curso Online

## Descrição

O Sistema de Gestão de Curso Online é uma aplicação web completa desenvolvida para gerenciar cursos, estudantes e matrículas. O sistema permite a criação e administração de cursos, cadastro de alunos, controle de matrículas e acompanhamento do progresso dos estudantes.

## Tecnologias Utilizadas

- **Frontend**: Nuxt.js com Nuxt UI
- **Backend**: Laravel (API RESTful)
- **Banco de Dados**: MySQL
- **Containerização**: Docker & Docker Compose

## Instalação e Configuração

### Pré-requisitos

- Docker e Docker Compose instalados
- Git
- Node.js (para desenvolvimento frontend)

### Passos para Instalação

1. **Clone o repositório**

   ```bash
   git clone git@github.com:davipessoadev/sistema-gesta-curso-online.git
   cd sistema-gesta-curso-online
   ```

2. **Configuração do Frontend**

   ```bash
   cd frontend
   npm install
   # ou
   yarn install
   ```

3. **Configuração das Variáveis de Ambiente**

   **Frontend**: Copie e configure o arquivo de variáveis de ambiente

   ```bash
   cp .env.example .env
   ```

   **Backend**: Copie e configure o arquivo de variáveis de ambiente

   ```bash
   cd ../backend
   cp .env.example .env
   ```

4. **Inicialização com Docker**

   ```bash
   # Na raiz do projeto
   docker-compose up -d
   ```

5. **Instalação das Dependências do Backend**

   ```bash
   docker exec -it -u root sistema_gestao_curso_onlie_api composer install
   # ou para atualizar
   docker exec -it -u root sistema_gestao_curso_onlie_api composer update
   ```

6. **Execução das Migrações**
   ```bash
   docker exec -it -u root sistema_gestao_curso_onlie_api php artisan migrate
   ```

## Decisões Técnicas

- **Nuxt UI**: Escolhido para acelerar o desenvolvimento do frontend, fornecendo componentes pré-construídos e uma interface moderna

## Acesso à Aplicação

Após a instalação e configuração completa, a aplicação estará disponível em:

**http://localhost**

---

_Documentação criada para o Sistema de Gestão de Curso Online_
