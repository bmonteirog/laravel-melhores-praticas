<?php

/*
  Nem todos os pacotes necessitam de um Service Provider, mas eles podem ser
  um local conveniente para organizar código de bootstrap e bindings do IoC.

  Neste exemplo vamos supor que nosso controller está utilizando Pusher para
  enviar mensagens para os clientes via WebSockets. Para não ficarmos
  dependentes do serviço, seria benéfico criar uma EventPusherInterface e também
  uma implementação PusherEventPusher.
*/

interface EventPusherInterface {
  public function push($message, array $data = array());
}

class PusherEventPusher implements EventPusherInterface {

  public function __construct(PusherSdk $pusher)
  {
    $this->pusher = $pusher;
  }

  public function push($message, array $data = array())
  {
    # Enviar a mensagem utilizando o SDK do Pusher
  }
}

# Agora vamos criar o EventPusherServiceProvider

use Illuminate\Support\ServiceProvider;

class EventPusherServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->singleton('PusherSdk', function() {
      return new PusherSdk('app-key', 'secret-key');
    });

    $this->app->singleton('EventPusherInterface', 'PusherEventPusher');
  }
}

/*
  Dessa maneira conseguimos uma boa abstração da emissão de eventos além de um
  local conveniente para registrar isso e fazer o bootstrap nas classes.

  Só precisamos adicionar o EventPusherServiceProvider no arquivo
  app/config/app.php para podermos injetar a EventPusherInterface em qualquer
  controller da aplicação com a dependência da PusherSdk já resolvida.
*/

/*
  Os Service Providers podem registrar qualquer tipo de serviço, como serviço de
  storage de arquivos, serviços de acesso à bases de dados, uma engine de
  template customizada como o Twig, etc. Eles são simplesmente ferramentas de
  bootstrap e organização para sua aplicação, nada mais.
  Não tenha medo de criar seus próprios Service Providers. Eles não são algo que
  deveriam estar presentes somente em pacotes distribuidos, mas deveriam ser
  utilizados como ferramentas organizacionais para suas próprias aplicações.
*/
