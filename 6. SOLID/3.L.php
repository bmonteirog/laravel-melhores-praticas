<?php

/*
  Liskov Substitution Principle

  Todo objeto deve poder ser substituído com instâncias de seus subtipos
  sem alterar a corretude do programa
*/

public function process(Order $order)
{

  // ...

  $this->orders->logOrder($order);
}

/*
  Se criássemos um novo OrderRepository que exige uma conexão teríamos que
  implementar assim:
*/

class DatabaseOrderRepository implements OrderRepositoryInterface {

  protected $connection;

  public function connect($username, $password)
  {
    $this->connection = new DatabaseConnection($username, $password);
  }

  public function logOrder(Order $order)
  {
    $this->connection->run('insert into orders values (?, ?)', array(
      $order->id, $order->amount
    ));
  }
}

public function process(Order $order)
{

  // ...

  if ($this->repository instanceof DatabaseOrderRepository)
  {
    $this->repository->connect('root', 'password');
  }
  $this->orders->logOrder($order);
}

/*
  Ou seja, ao substituir nosso OrderRepository pelo novo DatabaseOrderRepository
  foi necessário alterar a implementação das chamadas.

  Podemos corrigir da seguinte maneira:
*/

class DatabaseOrderRepository implements OrderRepositoryInterface {

  protected $connector;

  public function __construct(DatabaseConnector $connector)
  {
    $this->connector = $connector;
  }

  public function connect($username, $password)
  {
    $this->connector->bootConnection();
  }

  public function logOrder(Order $order)
  {
    $connection = $this->connect();

    $connection->run('insert into orders values (?, ?)', array(
      $order->id, $order->amount
    ));
  }
}

/*
  Agora nosso DatabaseOrderRepository está gerenciando a conexão e podemos tirar
  nosso código de bootstrap do OrderProcessor
*/
public function process(Order $order)
{

  // ...

  $this->orders->logOrder($order);
}
