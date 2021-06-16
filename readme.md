## **API REST em PHP 7.4.9**

API REST desenvolvida na linguagem PHP para matéria de Sistemas Distribuidos - UFPI à fim de aprender conceitos relacionadas à webservice.

Não segue nenhum modelo arquitetural de software, como MVC por exemplo.

## **Características e tecnologias**

- PHP 7.4.9
- Modelo REST
- Orientação à Objetos(POO)
- JSON
- Autoloading de classes
- Namespaces
- Apache 2.4.46
- Métodos GET, POST e DELETE

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

    - Frontend ainda em progresso, em breve update para usar banco de dados.

# Como usar?

- Faça login com seu ID único, caso não tenha, cadastre seu nome, e receberá seu ID único.
- Depois de logado, seu login será salvo para usar nas próximas opções de mensagens
- ao acessar a rota */usuarios/emails/,* todos os seus emails serão listados e divididos em Enviados e Recebidos.
- Para acessar um email em especificio, acesse */usuarios/emails/{id}, onde o ID é o ID Único da mensagem escolhida.*
- Para deletar acesse */usuarios/deletar/{id}, onde o ID é o ID Único da mensagem escolhida.*
- para enviar acesse */usuarios/enviar, no corpo você deve inserir* os campos Remetente, Destinatário, Assunto, e Corpo.
- para encaminhar acesse */usuarios/encaminhar, você insere o corpo com os dados id único e o remetente, o remetente também deve existir.*
- para responder acesse */usuarios/responder, no corpo da requisição você deve enviar o id único e o corpo da mensagem, que se refere texto que você quer enviar.*
