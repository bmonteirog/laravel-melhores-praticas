<?php

/*
  Single Responsability Principle

  Uma classe deve ter uma, e somente uma, razão para ser alterada.
*/

class OrderProcessor {

  public function __construct(BillerInterface $biller)
  {
    $this->biller = $biller;
  }

  public function process(Order $order)
  {
    $recent = $this->getRecentOrderCount($order);

    if($recent > 0)
    {
      throw new Exception("Duplicate order likely.");
    }

    $this->biller->bill($order->account->id, $order->amount);

    DB::table('orders')->insert( array(
      'account' => $order->account->id,
      'amount' => $order->amount,
      'created_at' => Carbon::now()
    ));
  }

  protected function getRecentOrderCount(Order $order)
  {
    $timestamp = Carbo::now()->subMinutes(5);

    return DB::table('orders')
                        ->where('account', $order->account->id)
                        ->where('created_at', '>=', $timestamps)
                        ->count();
  }
}

/*
  Responsabilidades da classe:
    Processar Orders, verifica se há orders dupicados na base
    Já precisaríamos alterar a classe em 2 ocasiões, caso o armazenamento de
    dados mude e também caso as regras de validação mudem.

  Nós precisamos extrair essa responsabilidade para outra classe, como uma
  OrderRepository
*/

class OrderRepository {

  public function getRecentOrderCount(Account $account)
  {
    $timestamp = Carbo::now()->subMinutes(5);

    return DB::table('orders')
                        ->where('account', $order->account->id)
                        ->where('created_at', '>=', $timestamps)
                        ->count();
  }

  public function logOrder(Order $order)
  {
    DB::table('orders')->insert( array(
      'account' => $order->account->id,
      'amount' => $order->amount,
      'created_at' => Carbon::now()
    ));
  }
}

/*
  Agora podemos injetar nosso Repositório na nossa OrderProcessor
*/

class OrderProcessor {

  public function __construct(BillerInterface $biller,
                              OrderRepository $orders)
  {
    $this->biller = $biller;
    $this->orders = $orders;
  }

  public function process(Order $order)
  {
    $recent = $this->orders->getRecentOrderCount($order->account);

    if($recent > 0)
    {
      throw new Exception("Duplicate order likely.");
    }

    $this->biller->bill($order->account->id, $order->amount);

    $this->orders->logOrder($order);
  }
}
