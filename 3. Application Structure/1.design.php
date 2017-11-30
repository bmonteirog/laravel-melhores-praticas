<?php

/*
  HMVC Normalmente indica um design ruim

  Ao desenvolver um sistema MVC é comum começar a acumular lógica dentro dos
  Controllers. Em determinado momento, quando esses Controllers estiverem
  grandes eles vão precisar reutilizar lógica de negócio que está em outros
  Controllers. Ao invés de extrair a lógica para uma outra classe, muitos
  desenvolvedores assumem que precisam chamar Controllers de dentro de outros
  Controllers. Isso indica uma decisão ruim de design, atribuindo mais
  responsabilidades ao Controllers do que o devido (Receber a requisição e
  devolver uma resposta).

  Para evitar esse caso e manter nossos Controllers magros, o correto é extrair
  essa lógica para uma classe que pode ser injetada nos Controllers.

*/
