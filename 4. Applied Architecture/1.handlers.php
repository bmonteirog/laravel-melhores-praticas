<?php

/*
  Os handlers da aplicação são considerados componentes da camada de transporte,
  eles recebem chamadas através de algo como Queue Workers (Jobs), eventos
  lançados (Listeners) ou requisições que chegam à aplicação (Route Filters).
  
  Devemos tratar nossos handlers assim como tratamos nossos Controllers, ou
  seja, devemos mantê-los enxutos e ignorantes a respeito de detalhes de
  implementação da sua aplicação.
  
  No nosso exemplo, vamos criar um handlers para um job na fila que envia uma
  mensagem SMS para um usuário e depois loga o envio.
  
  Inicialmente poderíamos ter algo assim:
*/

class SendSMS {
  
  public function fire($job, $data)
  {
    // Instancia o client do Twilio
    $twilio = new Twilio_SMS($apiKey);
    
    // Enviar a mensagem
    $twilio->sendTextMessage(array(
      'to'      => $data['user']['phone_number'],
      'message' => $data['message']
    ));
      
    // Buscar o usuário
    $user = User::find($data['user']['id']);
    
    // Cadastrar a mensagem no banco
    $user->messages()->create([
      'to'      => $data['user']['phone_number'],
      'message' => $data['message']
    ]);
    
    // Remover o job
    $job->delete();
  }
}

/*
  Problemas:
  - Classe difícil de testar
  - Como estamos instanciando a classe Twilio_SMS dentro do método fire()
    não poderemos injetar um mock para realizar os testes
  - Como estamos utilizando o Eloquent dentro do método, precisaremos 
    consultar uma base real em todos os testes
  - Outro ponto é que dessa maneira não podemos enviar um SMS de qualquer
    ponto da aplicação. Toda a lógica de envio está fortemente acoplada
    à fila do Laravel.
    
    Podemos deixar essa classe mais testável e desacoplar a lógica de envio
    de SMS da fila.
*/

class User extends Eloquent {
  
  /**
   * Envia uma mensagem SMS para o usuário
   *
   * @param SmsCourierInterface $courier
   * @param string $message
   * @return SmsMessage
   */
  public function sendSmsMessage(SmsCourierInterface $courier, $message)
  {
      // Enviar a mensagem
      $courier->sendMessage($this->phone_number, $message);
      
      // Cadastrar a mensagem no banco
      return $this->sms()->create([
        'to' => $this->phone_number,
      ]);
  }
}

class SendSMS {

    public function __construct(UserRepository $users, SmsCourierInterface $courier)
    {
      $this->users = $users;
      $this->courier = $courier;
    }
    
    public function fire($job, $data)
    {
      // Buscar o usuário
      $user = $this->users->find($data['user']['id']);
      
      // Enviar a mensagem
      $user->sendSmsMessage($this->courier, $data['message']);
      
      // Remover o job
      $job->delete();
    }
}

/*
  Nesse novo formato nosso handler é muito mais enxuto. Ele essencialmente
  serve como uma camada de tradução entre a file a lógica da sua aplicação real.
  E agora podemos facilmente enviar mensagens fora do contexto da fila.
*/















