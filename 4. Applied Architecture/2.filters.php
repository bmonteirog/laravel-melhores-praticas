<?php

/*
  Outro exemplo de otimização de handlers utilizando filtros de rota
*/

Route::filter('premium', function() {
  return Auth::user() && Auth::user()->plan == 'premium';
});

/*
  Mesmo sendo um exemplo pequeno e aparentemente inocente, nesse filtro 
  estamos vazando detalhes de implementação da nossa aplicação.
  
  Como estamos verificando manualmente o valor de plan, estamos acoplando
  a representação de planos da nossa camada de regra de negócio na camada
  de roteamento/transporte. Se mudássemos os critérios para definir se um
  plano é premium ou não na base de dados precisaríamos verificar aqui também.
  
  Isso é resolvível facilmente:
*/

Route::filter('premium', function() {
  return Auth::user() && Auth::user()->isPremium();
});

/*  
  Essa pequena alteração tem grandes benefícios e um custo muito pequeno.
  Deferindo a determinação de situação de conta do usuário para o model,
  nós removemos todos os detalhes de implementação do nosso filtro de rota.
  Esse filtro não é mais responsável por saber como determinar se um usuário
  é premium ou não.
  
  Se a lógica de usuários premium mudar na base de dados, não é necessário
  alterar esse filtro.
  
  Lembre-se sempre de questionar qual a responsabilidade de cada classe.
  Evite fazer sua camada de transporte, como os handlers, saberem sobre suas
  regras de negócio/aplicação.
*/