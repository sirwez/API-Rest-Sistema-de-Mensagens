# Lista de Funcionalidades Sistema de Mensagens | SD

Autor: Weslley de Aquino Ferreira

Disciplina: Sistemas Distribuídos

## **API REST em PHP 7.4.9**

API REST desenvolvida na linguagem PHP para matéria de Sistemas Distribuidos - UFPI à fim de aprender conceitos relacionadas à webservice.

Não segue nenhum modelo arquitetural de software, como MVC por exemplo.

## **Características e tecnologias**

- PHP 7.4.9
- Modelo REST
- Orientação à Objetos(POO)
- JSON
- Apache 2.4.46
- Métodos GET, POST e DELETE

## **Sistema**

- Windows 10
- Apache 2.4.46
- Não testado em sistema Linux.

## Campos dos Arquivos JSON

### **Users**

- id: "auto-incremente"
- nome: nome do usuário

### **Temp**

- Armazena um ID para acessar os registros referentes ao sistema de mensagens da api

### **Mensagem**

- id: identificação de quem está acessando a mensagem
- uniqueID: indentificador da mensagem, cada mensagem, sejá resposta, encaminhamento, ou envios tem ids próprios.
- remetente: quem envia
- destinatario: quem recebe
- assunto: assunto da mensagem
- corpo: corpo da mensagem
- lida: identificador para saber se mensagem foi aberta
- resposta: identificador se a mensagem é uma resposta
- encaminhada: identificador se a mensagem é encaminhada

# Post - Mensagens

- [x]  Enviar mensagem
- Recebe os campos Remetente, Destinatário, Assunto, e Corpo.
- *Rota :/usuarios/enviar*
- [x]  Responder Mensagem
- Recebe um json com os campos UniqueID, e o corpo da mensagem.
- *Rota :/usuarios/responder*
- [x]  Encaminhar mensagem
- Recebe os campos uniqueID para referenciar a mensagem que está encaminhando, e recebe também o destinatario.
- *Rota: /usuarios/encaminhar*

# GET - Mensagens

- [x]  Listar mensagen
- Lista mensagens pelo ID de quem está logado, retorna um array com dois campos: Enviados, e Recebidos.
- *Rota: /usuarios/emails/*
- [x]  Abrir mensagem
- Lista a mensagem desejada pelo uniqueID setado
- *Rota: /usuarios/emails/{id}*

# DELETE - Mensagens

- [x]  Apagar mensagens
- Recebe o ID Único da mensagem.
- *Rota: /usuarios/deletar/{id}*

# GET - Usuários

- [x]  Logar
- recebe o ID do usuário e verifica se existe ou não (ID obrigatoriamente numérico)
- *Rota: /usuarios/login/{id}*

# POST - Usuários

- [x]  Cadastrar
- Usuário cadastra seu nome e recebe um ID único gerado pelo sistema.
- Sistema de senhas não implementado
- *Rota: /usuarios/cadastrar*

# Outros

- [x]  rotas
- [ ]  interface
- Observações:

    - frontend ainda em progresso

# Como usar?

- Faça login com seu ID único, caso não tenha, cadastre seu nome, e receberá seu ID único.
- Depois de logado, seu login será salvo para usar nas próximas opções de mensagens
- ao acessar a rota */usuarios/emails/,* todos os seus emails serão listados e divididos em Enviados e Recebidos.
- Para acessar um email em especificio, acesse */usuarios/emails/{id}, onde o ID é o ID Único da mensagem escolhida.*
- Para deletar acesse */usuarios/deletar/{id}, onde o ID é o ID Único da mensagem escolhida.*
- para enviar acesse */usuarios/enviar, no corpo você deve inserir* os campos Remetente, Destinatário, Assunto, e Corpo.
- para encaminhar acesse */usuarios/encaminhar, você insere o corpo com os dados id único e o remetente, o remetente também deve existir.*
- para responder acesse */usuarios/responder, no corpo da requisição você deve enviar o id único e o corpo da mensagem, que se refere texto que você quer enviar.*

O sistema tem duas mensagens e dois usuários (Ismael, e Weslley) pré-cadastrados.

Imagens de funcionamento do 'Sistema de Mensagens' usado pela ferramenta de testes de APIs REST **INSOMNIA.**

### lOGIN:

![](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/login.jpg)

                          Login usando ID 1, usuário já cadastrado.

### CADASTRO:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/cadastrar.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/cadastrar.jpg)

                 Cadastro do usuário Cesar, e retornando seu ID de login.

### EMAILS:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/emailsListar.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/emailsListar.jpg)

                         Listagem de todos os emails do usuário Ismael.

### EMAIL INDIVIDUAL:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/listar_por_ID.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/listar%20por%20ID.jpg)

                       Mostrando um email individualmente, como dito nas observações, esse email é setado pelo ID únicode mensagem.

### ENVIAR:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/enviar.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/enviar.jpg)

### RESPONDER:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/responder.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/responder.jpg)

                          Respondendo ao email cujo ID único é 2.

### ENCAMINHAR:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/encaminhada.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/encaminhada.jpg)

                           Encaminhando um email para o usuário Cesar 

### DELETADO:

![Lista%20de%20Funcionalidades%20Sistema%20de%20Mensagens%20SD%2026dbc7808b484a8c94e49dc15b8b52ae/deletado.jpg](https://github.com/sirwez/API-Rest-Sistema-de-Mensagens/blob/21b234820b34ed6506d82b63ce4046f81f2604e0/img/deletado.jpg)

                                      Mensagem com ID Único deletada
