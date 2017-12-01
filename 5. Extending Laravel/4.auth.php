<?php

Auth::extend('riak', function($app) {
  // Return Illuminate\Auth\UserProviderInterface
  return new RiakUserProvider($app['riak.connection']);
});

interface UserProviderInterface {

  # Retorna uma classe que implementa UserInterface pelo id
  public function retrieveById($identifier);

  # Retorna uma classe que implementa UserInterface pelo campo de login
  public function retrieveByCredentials(array $credentials);

  # Valida as credenciais de um Usuario
  public function validateCredentials(UserInterface $user, array $credentials);
}

interface UserInterface {

  # Retorna a chave primária do usuário
  public function getAuthIdentifier();

  # Retorna o hash da senha do usuário
  public function getAuthPassword();
}
