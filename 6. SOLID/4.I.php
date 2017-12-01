<?php

/*
  Interface Segregation Principle

  Nenhuma implementação de interface deve ser forçada a depender de métodos que
  ela não utiliza. Segregar as interfaces em unidades menores e mais focadas ao
  invés de uma interface gorda com métodos específicos à certas implementações.
*/

interface SessionHandlerInterface{
  public function close() {}
  public function destroy($sessionId) {}
  public function gc($maxLifetime) {}
  public function open($savePath, $name) {}
  public function read($sessionId) {}
  public function write($sessionId, $sessionData) {}
}

/*
  Em uma implementação Memcached dessa interface não precisaríamos de metade
  desses métodos.
  Podemos resolver isso dividindo em interfaces menores:
*/

interface GarbageCollectorInterface {
  public function gc($maxLifetime);
}

/*
  Outro exemplo utilizando uma classe Eloquen
*/

class Contact extends Eloquent {

  public function getNameAttribute()
  {
    return $this->attributes['name'];
  }

  public function getEmailAttribute()
  {
    return $this->attributes['email'];
  }
}

class PasswordReminder {

  public function remind(Contact $contact, $view){
    // Enviar e-mail de recuperação
  }
}

/*
  Nosso método de envio de email de recuperação de senha agora é dependente do
  ORM Eloquent. Ele sabe demais sobre a implementação.
*/

interface RemindableInterface {
  public function getReminderEmail();
}

class Contact extends Eloquent implements RemindableInterface {

  public function getReminderEmail()
  {
    return $this->email;
  }
}

class PasswordReminder {

  public function remind(RemindableInterface $remindable, $view){
    // Enviar e-mail de recuperação
  }
}
