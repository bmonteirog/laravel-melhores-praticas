<?php

/*
  Para extender o serviço de Cache nós usaremos o método extend no CacheManager
*/

class MongoStore implements Illuminate\Cache\StoreInterface {

  public function get($key) {}
  public function put($key, $value, $minutes) {}
  public function increment($key, $value = 1) {}
  public function decrement($key, $value = 1) {}
  public function forever($key, $value) {}
  public function forget($key) {}
  public function flush() {}
}

Cache::extend('mongo', function($app) {
  // Return Illuminate\Cache\Repository instance...
  return new Illuminate\Cache\Repository(new MongoStore);
});

/*
  Podemos deixar o arquivo da implementação do nosso Driver em um diretório
  Extensions, como app\QuickBill\Extensions\MongoStore.php

  E o Binding podemos armazenar um Service Provider
*/
