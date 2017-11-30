<?php

/*
  O Ioc Container (Inversion of Control Container) é responsável por fazer a
  inversão de dependência no Laravel. Ele é acessado através da facade App.
  É nele que registramos quais classes serão instanciadas quando uma interface for
  solicitada em algum construtor.
*/

App::bind('BillerInterface', function() {
  return new StripeBiller( App:make('BillingNotifierInterface') );
});

App::bind('BillingNotifierInterface', function() {
  return new EmailBillingNotifier;
});

/*
  Dentro da própria definição de inversão de dependência podemos referenciar
  outra interface que será resolvida também.

  Através IoC Container também é possível definir singletons para obter sempre
  a mesma instância de uma classe quando resolvida.
*/

App::singleton('BillingNotifierInterface', function() {
  return new SmsBillingNotifier;
});

/* Também é possível fazer o bind a um objeto existente */

$notifier = new SmsBillingNotifier;
App:instance('BillingNotifierInterface', $notifier);
