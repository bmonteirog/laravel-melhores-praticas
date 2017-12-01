<?php

/*
  Temos duas maneiras de extender o framework: Através de *bindings no container
  IoC*, ou registrando uma extensão com a *classe Manager*, que são implementações
  do padrão de design Factory.


  AS classes Manager do Laravel são classes que gerenciam a criação de componentes
  do tipo driver, como cache, session, auth, queue.

  Ele lê as configurações de driver e instancia as classes apropriadas. O
  CacheManager pode criar um driver APC, Memcached, Native entre outras
  implementações.

  Todos os Managers tem um método extend() que pode ser usado para injetar novos
  drivers no Manager.

*/
