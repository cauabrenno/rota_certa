# 🛵 RotaCerta - Sistema de Gestão de Entregas

O RotaCerta é uma plataforma completa e reativa para o gerenciamento de pedidos e entregas, conectando Clientes, Lojistas e Entregadores em um fluxo síncrono e seguro.

## 🚀 Tecnologias Utilizadas
* **Front-end:** Vue.js, Vue Router, CSS customizado (sem frameworks engessados).
* **Back-end:** PHP / Laravel.
* **Banco de Dados:** PostgreSQL.

## ✨ Principais Funcionalidades
* **Três Painéis Independentes:** Interfaces dedicadas e protegidas por Navigation Guards para Clientes, Lojistas e Entregadores.
* **Atualização em Tempo Real:** Mecanismo de Long Polling para transição de status de pedidos instantânea sem necessidade de reload.
* **Cálculo de Frete Dinâmico:** Precificação automatizada baseada na distância real entre o estabelecimento e o cliente.
* **Sistema de Avaliações:** Feedback interativo estilo iFood para entregadores.
* **Segurança de Código:** Autenticação de entrega em duas pontas, garantindo que o código não vaze nas requisições da API.

## 💻 Padrão de Código e Arquitetura
Este projeto segue uma regra rígida e inegociável de **Código Limpo e Explícito**.
É terminantemente proibida a utilização de abreviações no código-fonte. Todas as variáveis, funções, classes, tabelas e métodos possuem nomes descritivos e completos (ex: `identificadorDoIntervaloDeAtualizacao` em vez de `intervalId`), garantindo altíssima legibilidade e facilitando a manutenção pela equipe.

## 🛠️ Como rodar o projeto localmente

### Pré-requisitos
* Node.js
* PHP 8+ e Composer
* PostgreSQL

### Configurando o Back-end
1. Acesse a pasta `backend/`
2. Rode `composer install`
3. Copie o `.env.example` para `.env` e configure o banco de dados.
4. Rode `php artisan key:generate`
5. Rode `php artisan migrate`
6. Crie o link simbólico das imagens com `php artisan storage:link`
7. Inicie o servidor com `php artisan serve`

### Configurando o Front-end
1. Acesse a pasta `frontend/`
2. Rode `npm install`
3. Inicie o servidor com `npm run dev`
