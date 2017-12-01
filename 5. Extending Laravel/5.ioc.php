<?php

/*
  Para extender componentes do Framework que não são drivers, como os Service
  Providers, é possível criar suas próprias classes e trocar o binding no
  arquivo de configuração.
*/

namespace app\QuickBill\Extensions\Pagination;

class Environment extends \Illuminate\Pagination\Environment {

}

class QuickBillPaginationProvider extends PaginationServiceProvider {

  public function boot() {

    App::bind('paginator', function() {
      return new app\QuickBill\Extensions\Pagination\Environment;
    });

    parent:boot();
    
  }
}
