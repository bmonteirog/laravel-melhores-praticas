<?php

/*
  Para criar uma aplicação chamada QuickBill vamos criar um diretório QuickBill
  no mesmo nível do diretório da aplicação.

  Para adicionar nossas classes no autoload do composer:

  "autoload": {
    "psr-0": {
      "QuickBill": "app/"
    }
  }

  Manter os Models na raiz da aplicação nos permite acessar nossas entidades
  Eloquent que representam o domínio de maneira simples, como QuickBill\User e
  QuickBill\Payment.

  No diretório Repositories colocaremos classes como PaymentRepository e
  UserRepository que conterão todos nossos métodos de acesso aos dados como
  getRichestUser() e getRecentPayments().

  O diretório Billing conterá classes e interfaces que interagem com serviços
  trird-party de billing, como Stripe e Balanced.

  // app
      // Extensions
          -> PaginatorExtension.php
      // QuickBill
          // Repositories
              -> UserRepository.php
              -> PaymentRepository.php
          // Billing
              -> BillerInterface.php
              -> StripeBiller.php
          // Notifications
              -> BillingNotifierInterface.php
              -> SmsBillingNotifier.php
          // Form Request (Laravel 5.1 >)
              -> UserRequest.php
              -> PaymentRequest.php
      User.php
      Payment.php

  Não tenha medo de criar mais diretórios para organizar sua aplicação. Sempre
  quebre sua aplicação em componentes menores, cada um tendo uma responsabilidade
  bastante focada.

  É tudo sobre camadas

  Um ponto chave de um bom design de aplicação é simplesmente separação de
  responsabilidades em camadas. Controllers são responsáveis por receber uma
  chamada HTTP e chamar as classes corretas da camada de negócio.
  Sua camada de negócio/domínio É sua aplicação. Contém classes que trazem dados,
  que validam dados, processam pagamentos, enviam e-mails, e outros.

  Um maneira de isolar a camada web da aplicação da camada de domínio é enviar
  os dados da requisição para o domínio ao invés de um objeto Request.
*/

class BillingController extends BaseController {

  public function __construct(BillerInterface $biller) {
    $this->biller = $biller;
  }

  public function postCharge() {
    $this->biller->chargeAccount(Auth::user(), Input::get('amount'));
    return View::make('charge.success');
  }
}

/*
  Agora nosso método chargeAccount é muito mais simples de testar, sendo que não
  precisamos usar a facade Input ou Request dentro da nossa implementação de
  BillerInterface.

  Separação de responsabilidades é um fator chave na hora de escrever aplicações
  manuteníveis. Sempre se pergunte se uma determinada classe sabe mais do que ela
  deveria. Sempre se pergunte "Essa classe deveria se importar com X?" Se a
  resposta for "não", extraia a lógica para uma outra classe que pode ser injetada
  como dependência.
*/
