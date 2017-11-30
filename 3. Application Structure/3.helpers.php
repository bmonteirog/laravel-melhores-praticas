<?php

/*

  Helpers podem ser criados em um diretório na raiz da aplicação e podem
  ser carregados tanto pelo composer como podem ser incluidos em um arquivo de
  bootstrap
*/

// Dir     app/Helpers/
// Arquivo app/start/global.php (Laravel 4.1)

require_once __DIR__.'/../Helpers/ThumbHelper.php';


// Composer

"autoload": {
  "files": [
    "app/Helpers/ThumbHelper.php"
  ]
},
