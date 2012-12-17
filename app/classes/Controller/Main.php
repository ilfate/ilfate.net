<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of Main
 *
 * @author ilfate
 */
class Controller_Main extends Controller {
  //put your code here
  
  /**
   * 
   * @return type 
   */
  public function index() 
  {
    //self::cache('aaa', 'bbb', 'ccc');
    
        //Model_User::createUserWithEmail('email', 'pass', '$name');
	    
    return array();
  }
  
  /**
   * 
   * @return type 
   */
  public function aboutMe() 
  {
      
    return array(
      'mode' => Request::EXECUTE_MODE_HTTP,
      'tpl' => 'Main/aboutMe.tpl'
    );
  }
  
  /**
   * @cache 10 tag tag2aw[1] tags t2[2][0]
   * @return type 
   */
  public static function _cache() {
    Logger::dump('_cache method. no chache<br>');
    return array();
  }
  
  public function mysql()
  {
    $user = Model_User::getByPK(3);
    //Logger::dump($user->name);
    $user->name = 'masha_' . mt_rand(1000, 9999);
    $user->save();
    //$user2 = new Model_User(array('id' => 6, 'name' => 'ilfate', 'email' => mt_rand(1000, 9999).'@mail.com'));
    //$user2->save();
    $users = Model_User::getValue('email',' id > ?', array(3));
    Logger::dump($users);
//    foreach ($users as $id => $user)
//    {
//      dump('id = '. $id .' name ='.$user->name .' email = '. $user->email.'<br>');
//    }
    return array(
      'tpl' => 'Main/index.tpl'
    );
  }
  
  /**
   * 
   * @return type 
   */
  public function randomBanner()
  {
    $random_elements = array(
      array(
        'url'   => Helper::url('Main', 'codeEngine'),
        'text'  => 'Ilfate PHP framework',
        'image' => '/images/php2.jpg'
      ),
      array(
        'url'   => Helper::url('Cv'),
        'text'  => 'Ilya Rubinchik',
        'image' => '/images/my/ilfate.png'
      ),
      array(
        'url'   => Helper::url('Main', 'codeStarred'),
        'text'  => 'Starred label',
        'image' => '/images/js2.jpg'
      ),
      array(
        'url'   => Helper::url('RobotRock'),
        'text'  => 'RobotRock',
        'image' => '/images/game/tank4-2.jpg'
      ),
    );
    $data = $random_elements[array_rand($random_elements)];
    return array(
      'url' => $data['url'],
      'text' => $data['text'],
      'image' => $data['image'],
      'tpl' => 'Main/randomBanner.tpl'
    );
  }
  
  /**
   * @cache 15
   * @return type 
   */
  public function _Menu() 
  {
    Logger::dump('menu no cahche');
    return array();
  }
  
  public function flush()
  {
    Cache::flush();
    Helper::redirect('Main', 'index');
  }
  
  public function code()
  {
    return array(
      'tpl' => 'Main/code.tpl',
	  );
  }
  public function codeEngine()
  {
    return array(
      'tpl' => 'Main/codeEngine.tpl',
	  );
  }
  public function codeStarred()
  {
    return array(
      'tpl' => 'Main/codeStarred.tpl',
	  );
  }
  
  public function photo()
  {
	$image = Request::getParameter('photo');
	if(!$image) 
	{
		$image = 1;
	}
	Js::add(Js::C_ONLOAD, 'Photo.createRows(800,220,'.$image.')');
    return array(
      'images_gallery' => array(
          array('img' => '/images/my/tu1.jpg'),
		  array('img' => '/images/my/snow1.jpg', 'down-shift' => 0.1),
		  array('img' => '/images/my/code1.jpg'),
		  array('img' => '/images/my/snow3.jpg'),
          array('img' => '/images/my/ilfate2.jpg'),
		  array('img' => '/images/my/ilfate2.png'),
          array('img' => '/images/my/tu2.jpg'),
          array('img' => '/images/my/me1.jpg'),
          array('img' => '/images/my/snow0.jpg'),
          array('img' => '/images/my/we1.jpg'),
          array('img' => '/images/my/snow2.jpg'),
          array('img' => '/images/my/snow4.jpg'),
          array('img' => '/images/my/is1.jpg'),
          array('img' => '/images/my/snow5.jpg', 'down-shift' => 0.1),
      ),
      'tpl' => 'Main/photo_content.tpl',
      'layout' => array('html.tpl', 'head.tpl', 'Main/photo.tpl')
	  );
  }
}

?>
