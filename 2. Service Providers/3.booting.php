<?php

/*
  O método register() dos Service Providers devem ser utilizados somente para
  registrar os bindings, e nesse momento nenhum bind ainda pode ser resolvido.

  Para utilizar um binding resolvido é necessário fazer a operação no método
  boot(), onde já é possível utilizar objetos resolvidos pelo IoC.

  Dentro do boot() você pode fazer o que quiser, como registrar listeners de
  eventos, incluir arquivos de rotas ou qualquer outra coisa.
*/

use Illuminate\Support\ServiceProvider;

class EventPusherServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->singleton('PusherSdk', function() {
      return new PusherSdk('app-key', 'secret-key');
    });

    $this->app->singleton('EventPusherInterface', 'PusherEventPusher');
  }

  public function boot()
  {
    require_once __DIR__'./events.php';

    require_once __DIR__'./routes.php';
  }
}
