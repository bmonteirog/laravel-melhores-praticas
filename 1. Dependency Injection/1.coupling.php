<?php

class UserController extends BaseController {

  public function getIndex() {
    $users = User::all();

    return View::make('users.index', compact('users'));
  }
}

/*
  Embora seja um código consciso, nosso controller agora possui alto grau de
  acoplamento com o nosso model. Além disso nossa classe está violando o
  princípio de separação de responsabilidades. O Controller não precisa saber
  de onde vem os dados, no caso o Model, só precisa recebê-los e encaminhá-los
  para a response.

  Além disso, o controller é impossível de ser testado sem que o teste faça uma
  chamada à base de dados à qual o Model se conecta.

  Sendo assim, seria benéfico separar nossas camadas de rede (controller) da
  camada de acesso de dados (model).

  Para isso, vamos injetar uma classe repositório:
*/

interface UserRepositoryInterface {
  public function all();
}

class DbUserRepository implements UserRepositoryInterface {

  public function all() {
    return User::all()->toArray();
  }
}

class UserController extends BaseController {

  protected $users;

  public function __construct(UserRepositoryInterface $users) {
    $this->users = $users;
  }

  public function getIndex() {

    $user = $this->users->all();

    return View('users.index', compact('users'));
  }
}

/*
  Agora nosso controller é ignorante em relação à origem dos dados. Ele se
  limita a aceitar uma implementação da interface que fornece um array de
  usuários como retorno para o método all();
  A origem pode ser tanto de uma base MySql quanto MongoDB ou Redis ou até mesmo
  um mock para testes.
*/
