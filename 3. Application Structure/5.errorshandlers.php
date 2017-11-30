<?php

/*
  O método boot também é útil para associarmos os Handlers de erro da aplicação
*/

namespace QuickBill\Providers;

use Illuminate\Support\ServiceProvider;

class BillingEventsProvider extends ServiceProvider {

  public function boot() {
    App::error( function(BillingFailedException $e) {
      // Lógica para exceção BillingFailedException
    });
  }
}
