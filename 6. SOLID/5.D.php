<?php

/*
  Dependency Inversion Principle

  -> Código de alto-nível não deve depender de código de baixo-nível
     (Deve sim depender de uma camada de abstração que serve como mediador - interface)
     Alto-nível (código que nós escrevemos)
     Baixo-nível (códigos de infraestrutura, como conectores, drivers, etc)

  -> Abstrações não devem depender de detalhes
*/

class Authenticator {

  public function __construct(DatabaseConnection $db)
  {
    $this->db = $db;
  }

  public function findUser($id)
  {
    return $this->db->exec('select * from users where id = ?', array($id));
  }

  public function authenticate($credentials)
  {
    // Autenticar usuário
  }
}

interface UserProviderInterface {
  public function find($id);
  public function findByUsername($username);
}

/* Agora vamos injetar nossa interface no Authenticator */

class Authenticator {

  public function __construct(UserProviderInterface $users,
                              HasherInterface $hash)
  {
    $this->hash = $hash;
    $this->users = $users;
  }

  public function findUser($id)
  {
    return $this->users->find($id);
  }

  public function authenticate($credentials)
  {
    $user = $this->users->findByUsername($credentials['username']);

    return $this->hash->make($credentials['password']) == $user->password;
  }
}
