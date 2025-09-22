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

### Passos para Instalação

1. **Clone o repositório**
   ```bash
   git clone git@github.com:davipessoadev/sistema-gesta-curso-online.git
   cd sistema-gesta-curso-online
   ```

2. **Configuração das Variáveis de Ambiente**
   
   **Frontend**: Copie e configure o arquivo de variáveis de ambiente
   ```bash
   cd frontend
   cp .env.example .env
   ```
   
   **Backend**: Copie e configure o arquivo de variáveis de ambiente
   ```bash
   cd ../backend
   cp .env.example .env
   ```

3. **Inicialização com Docker**
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
## Documentação da API
[Link pra documentação no ApiDog](https://5o233g45zz.apidog.io)

## Decisões Técnicas

- **Nuxt UI**: Escolhido para acelerar o desenvolvimento do frontend, fornecendo componentes pré-construídos e uma interface moderna
- **ApiDog**: Uma alternativa ao Postman ou Insomnia, e foi escolhida por disponibilizar documentação online e fácil de utilizar.
- **Zod**: Pela familiaridade já com a lib que uso para validar formulários de aplicações em ReactJS.

## Acesso à Aplicação

Após a instalação e configuração completa, a aplicação estará disponível em:

**http://localhost**

---

*Documentação criada para o Sistema de Gestão de Curso Online*
