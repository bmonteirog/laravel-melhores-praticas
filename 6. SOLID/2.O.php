<?php

/*
  Open Closed Principle

  As classes devem ser abertas para extensão mas fechadas para modificação
*/

$recent = $this->orders->getRecentOrderCount($order->account);

if ($recent > 0)
{
  throw new Exception("Duplicate order likely");
}

/*
  Esse código é legível inicialmente, mas toda vez que a regra de validação de
  pedido for alterada esse if vai precisar ser alterado também. Caso o número de
  regras cresça muito, a chance dessa verificação virar uma bagunça de código
  spaguetti é grande.

  Como ele precisará ser alterado, ele está aberto para alteração, vamos deixá-lo
  aberto para extensão. Começaremos criando uma interface OrderValidator
*/

interface OrderValidatorInterface {
  public function validate(Order $order);
}

class RecentOrderValidator implements OrderValidatorInterface {

  public function __construct(OrderRepository $orders)
  {
    $this->orders = $orders;
  }

  public function validate(Order $order)
  {
    $recent = $this->orders->getRecentOrderCount($order->account);

    if ($recent > 0)
    {
      throw new Exception("Duplicate order likely");
    }
  }
}

class SuspendedAccountValidator implements OrderValidatorInterface {

  public function validate(Order $order)
  {
    if ($order->account->isSuspended())
    {
      throw new Exception("Account suspended.");
    }
  }
}

/*
  Para usar nossas duas implementações de Validators vamos injetar um array de
  validadores na nossa classe OrderProcessor
*/

class OrderProcessor {

  public function __construct(BillerInterface $biller,
                              OrderRepository $orders,
                              array $validators = array())
  {
    $this->biller = $biller;
    $this->orders = $orders;
    $this->validators = $validators;
  }

  public function process(Order $order)
  {
    foreach ($this->validators as $validator) {
      $validator->validate($order);
    }

    // Order válido
  }
}

/*
  Faltou apenas regisrtar nosso OrderProcessor no container IoC
*/

App::bind('OrderProcessor', function()
{
  return new OrderProcessor(
    App::make('BillerInterface'),
    App::make('OrderRepository'),
    array(
      App::make('RecentOrderValidator'),
      App::make('SuspendedAccountValidator'),
    ),
  );
});

/*
  Abstrações transbordando. Sempre que uma alteração em uma dependência
  tornar necessário alterar também a classe que a consome dizemos que
  está havendo vazamento de responsabilidade. Quando isso ocorre, o princípio
  de aberto/fechado foi quebrado.
*/
