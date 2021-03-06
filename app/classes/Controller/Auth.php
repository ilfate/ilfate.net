<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of Auth
 *
 * @author ilfate
 */
class Controller_Auth extends Controller 
{
  
  
  /**
   * 
   * @return type 
   */
  public function logInForm() 
  {
    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Auth/loginForm.tpl',
      ''
    );
  }
  
  
  /**
   * 
   * @return type 
   */
  public function signUpForm() 
  {
    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Auth/signUpForm.tpl',
      ''
    );
  }
  
  /**
   * Submition of the registration form
   *
   * @return type 
   */
  public function signUp()
  {
    if(!Validator::validateForm(
      array(
        'email' => array('notEmpty', array('minLength', 3), 'email', 'userEmailUnique'),
        'pass'  => array('notEmpty', array('equalField', 'pass2'), array('minLength', 6)),
        'name'  => array('notEmpty', array('minLength', 4), array('maxLength', 16), 'userNameUnique'),
      )  
    ))
    {
      return Validator::getFormErrorAnswer();
    }
	
    $post = Service::getRequest()->getPost();
    $user = Model_User::createUserWithEmail($post['email'], $post['pass'], $post['name']);
    self::auth($user);
	  Message::add('Welcome!!');
    return array(
      'sucsess' => true,
      'actions' => array('Action.refresh'),
    );
  }

  public function signIn()
  {
    $config = array(
      'email' => array(array('authEmailAndPassword', 'password')),
    );
    if(!Validator::validateForm($config))
    {
      return Validator::getFormErrorAnswer();
    }
    $post = Service::getRequest()->getPost();
    $user = Model_User::getUserByEmailAndPassword($post['email'], $post['password']);
    self::auth($user);
    Message::add('Hi!!!');
    return array(
      'sucsess' => true,
      'actions' => array('Action.redirect'),
      'args'    => array('/')
    );
  }
  
  public function logOut()
  {
    Service::getRequest()->setSession(FrontController_Auth::SESSION_AUTH_KEY, null);
    Runtime::setCookie(Service_Auth::COOKIE_AUTH_KEY, null, null);
    return array(
      'sucsess' => true,
      'actions' => array('Action.redirect'),
      'args'    => array('/')
    );
  }
  
  
  public static function auth(Model_User $user)
  {
    Service::getRequest()->setSession(FrontController_Auth::SESSION_AUTH_KEY, array('id' => $user->id, 'time' => time()));
    Runtime::setCookie(FrontController_Auth::COOKIE_AUTH_KEY, $user->cookie, FrontController_Auth::COOKIE_AUTH_KEY_EXPIRES);
  }
  
  
  public function needRegistration()
  {
    return array();
  }
  
  
}

?>
