<?php

/*
  O método boot também é útil para associarmos os Listeners da aplicação
*/

namespace QuickBill\Providers;

use Illuminate\Support\ServiceProvider;

class BillingEventsProvider extends ServiceProvider {

  public function boot() {
    Event::listen('billing.failed', function($bill) {
      // Lógica para cobrança falha
    });
  }
}
