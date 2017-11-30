<?php

/*
  Reflection é um recurso do php que permite analisar classes e métodos.
  Utilizando este recurso o IoC é capaz de verificar quais classes são
  requeridas pelos métodos e instanciar as classes necessárias sem a necessidade
  de especificar os bindings um a um.
*/

$reflection = new ReflectionClass('StripeBiller');

var_dump($reflection->getMethods());

var_dump($reflection->getConstants());





class UserController extends BaseController {

  public function __construct(StripeBiller $biller)
  {
    $this->biller = $biller;
  }
}

/*
  O Fluxo do IoC para resolver é o seguinte:
  1. Eu possuo uma resolução para StripeBiller?
  2. Não? Então vamos refletir a classe StripeBiller para determinar se possui
     dependências.
  3. Resolver qualquer dependência de StripeBiller (recursivamente)
  4. Instanciar new StripeBiller via ReflectionClass->newInstanceArgs()
*/

class UserController extends BaseController {

  public function __construct(BillerInterface $biller)
  {
    $this->biller = $biller;
  }
}

App::bind('BillerInterface', 'StripeBiller');
App::bind('BillerInterface', 'BalancedBiller');
