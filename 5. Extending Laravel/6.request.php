<?php

/*
  Extender a classe de request pois ela é instanciada muito antes que as demais
*/

namespace app\QuickBill\Extensions;

class Request extends \Illuminate\Http\Request {
  // Métodos
}

# No arquivo bootstrap/start.php
$app = new \Illuminate\Foundation\Application;

use Illuminate\Foundation\Application;
Application::requestClass('QuickBill\Extensions\Request');
