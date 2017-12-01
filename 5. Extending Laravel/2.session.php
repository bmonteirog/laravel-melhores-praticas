<?php

/*
  Extender o driver de Session é similar, basta retornar uma instância de
  SessionHandlerInterface

  A SessionHandlerInterface é uma interface disponível no PHP 5.4 +, se a versão
  for 5.3 - o framework disponibiliza a interface no namespace raiz
*/

Session::extend('mongo', function($app) {
  // return SessionHandlerInterface
  return new MongoHandler;
});

class MongoHandler implements SessionHandlerInterface {
  public function open($savePath, $sessionName) {}
  public function close() {}
  public function read($sessionId) {}
  public function write($sessionId, $data) {}
  public function destroy($sessionId) {}
  public function gc($lifetime) {}
}

/*

Open:
  Tipicamente seria usado para abrir arquivos em sistemas de sessão baseados em
  arquivos, mas muitas vezes ficará vazio mesmo.

Close:
  Idem.

Read:
  Deve retornar uma string dos dados da sessão. O framework serializa
  automaticamente.

Write:
  Deve armazenar o valor no sistema de sessão.

Destroy:
  Deve remover os dados associados ao $sessionId

Gc:
  Deve destruir todos os dados de sessão que são mais antigos que um dado
  $lifetime. Para sistemas com auto-expiração como Memcached ou Redis, esse
  método pode ser deixado vazio.

*/
