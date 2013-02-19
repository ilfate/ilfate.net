<?php
/*
 * Task:
Надо написать класс, который будет заниматься склонением имен
собственных. В приложении можно найти небольшую табличку с правилами.
Она специально не такая большая и покрывает не все 100% имен
собственных, но для тестового задания очень удобная. Базы данных
использовать никакие не надо, они могут быть добавлены когда-нибудь в
будущем.
 English:
 You need to create class that will change Russian names declensions in all ways.
 There is a table of rules you can use for that.
 There is no need to use databases.
*/

/**
 * Class for name declension in russian
 * 
 * usage:
 *   $d = new Declension();
 *   var_dump($d->setPerson('f', 'Мария', 'Белова')->getAll());
 * 
 * unitTest:
 *   Declension::unitTest();
 * 
 * WARNING!
 * Make sure that your project use proper encoding:
 *   setlocale(LC_ALL, 'ru_RU.UTF-8');
 *   mb_internal_encoding('utf-8');
 *
 * @author Ilya Rubinchik ilfate@gmail.com
 */
class Declension
{
  /**
   * this is just table of rules we should use.
   *
   * @var array 
   */
  protected $rules = [
    0 => ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => self::CONSONANT],
    1 => ['sex' => [self::FEMALE], 'type' => [self::NAME], 'end' => self::CONSONANT, 'notEnd' => ['ь']],
    2 => ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ого', 'яго', 'ово', 'ых', 'их']],
    3 => ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['иа', 'ия']],
    4 => ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ко']],
    5 => ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME, self::NAME], 'end' => self::VOWEL, 'notEnd' => ['а', 'я']],
    6 => ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['х', 'ц']],
    
    7 => ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['ь'], 'cases' => ['я', 'ю', 'я', 'ем', 'е']],
    8 => ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['ий', 'ой'], 'cases' => ['ого', 'ому', 'ого', 'им', 'ом']],
    9 => ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['й'], 'cases' => ['я', 'ю', 'я', 'ем', 'е']],
    10 => ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['б', 'г', 'д', 'з', 'к', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ч'], 'before' => ['а'], 
    'cases' => ['а', 'у', 'а', 'ом', 'е']],
    11 => ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['ж', 'ч', 'ш', 'щ', 'ц'], 'cases' => ['а', 'у', 'а', 'ем', 'е']],
    12 => ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['в', 'л'], 'cases' => ['а', 'у', 'а', 'ым', 'е']],
    13 => ['sex' => [self::MALE], 'type' => [self::NAME], 'end' => self::CONSONANT, 'cases' => ['а', 'у', 'а', 'ом', 'е']],
    14 => ['sex' => [self::FEMALE], 'type' => [self::NAME], 'end' => ['ь'], 'cases' => ['и', 'и', 'ь', 'ь', 'и']],
    15 => ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ая'], 'cases' => ['ой', 'ой', 'ую', 'ой', 'ой']],
    16 => ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => ['а'], 'before' => self::CONSONANT, 'cases' => ['ой', 'ой', 'у', 'ой', 'ой']],
    17 => ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => ['я'], 'before' => self::CONSONANT, 'cases' => ['и', 'е', 'ю', 'ой', 'е']],
    18 => ['sex' => [self::MALE, self::FEMALE], 'type' => [self::NAME], 'end' => ['а'], 'cases' => ['ы', 'е', 'у', 'ой', 'е']],
    19 => ['sex' => [self::MALE, self::FEMALE], 'type' => [self::NAME], 'end' => ['я'], 'cases' => ['и', 'е', 'ю', 'ей', 'е']]
  ];
  protected $possibleSex = [
    'm' => ['m', 'м', 'man', 'male', 'муж'],
    'f' => ['f', 'ж', 'women', 'female', 'жен']
  ];
  protected $vowels = ['а', 'у', 'о', 'ы', 'и', 'э', 'я', 'ю', 'ё', 'е'];
  protected $consonants = ['б', 'в', 'г', 'д', 'ж', 'з', 'й', 'к', 'л', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ь'];
  /**
   * all possible cases
   * @var array 
   */
  protected $cases = [
    'genitive',      // 'Родительный'
    'dative',        // 'Дательный'
    'accusative',    // 'Винительный'
    'ablative',      // 'Творительный'
    'prepositional', // 'Предложный'
  ];
  
  protected $exceptionLib = [];
  
  const MALE      = 'm';
  const FEMALE    = 'f';
  const NAME      = 'name';
  const SURNAME   = 'surname';
  const VOWEL     = 'vowel';
  const CONSONANT = 'consonant';
  
  protected $possibleTypes = [
    self::NAME,
    self::SURNAME
  ];
  
  protected $name;
  protected $surname;
  protected $sex;
  protected $matched_rules;
  protected $matched_ends;

  /**
   * Sets new person for declension
   * Provide nominative data.
   * 
   * @param String|Integer $sex
   * @param String $name
   * @param String $surname
   * @return \Declension
   * @throws Exception
   */
  public function setPerson($sex, $name = '', $surname = '')
  {
    if(!$name && !$surname)
    {
      throw new Exception('Both name and surname must not be empty.');
    }
    $this->name = $name;
    $this->surname = $surname;
    $this->setSex($sex);
    $this->matched_rules = [];
    return $this;
  }
  
  /**
   * Sets person`s sex
   * 
   * @param String|Integer $sex
   * @return \Declension
   * @throws Exception
   */
  public function setSex($sex)
  {
    if(in_array($sex, $this->possibleSex[self::MALE]))
    {
      $this->sex = self::MALE;
    } elseif(in_array($sex, $this->possibleSex[self::FEMALE])) {
      $this->sex = self::FEMALE;
    } else {
      throw new Exception('Sex error =). Please provide correct sex ("m" or "f")');
    }
    return $this;
  }
  
  /**
   * this function returns all cases in array
   * 
   * @return array 
   */
  public function getAll()
  {
    $return = [];
    foreach ($this->possibleTypes as $type) 
    {
      if(!empty($this->$type)) 
      {
        $this->findRule($type);
        foreach ($this->cases as $case) 
        {
          if(!isset($return[$case])) $return[$case] = [];
          $return[$case][$type] = $this->getWordByCase($type, $case);
        }
      }
    }
    return $return;
  }
  
  // this function defines witch rule we are going to use
  protected function findRule($type)
  {
    if(!empty($this->matched_rules[$type])) 
    {  // this word was already mached
      return $this->matched_rules[$type];
    }
    if(!$this->$type)
    {  // it is just empty.
      return false;
    }
    // ok lets try to find rule
    foreach ($this->rules as $id_rule => &$rule) 
    {
      if(!in_array($this->sex, $rule['sex']) || !in_array($type, $rule['type']))
      { // this rule isnt mach
        continue;
      }
      if(!is_array($rule['end']))
      {
        if($rule['end'] == self::CONSONANT) $rule['end'] = $this->consonants;
        if($rule['end'] == self::VOWEL)     $rule['end'] = $this->vowels;
      }
      $matched_end = false;
      foreach ($rule['end'] as $possible_end) 
      {
        if(mb_strrchr($this->$type, $possible_end) == $possible_end) 
        { // ok we found ending match here
          $matched_end = $possible_end;
          break;
        }
      }
      if($matched_end)
      {
        if(isset($rule['notEnd']) && in_array($matched_end, $rule['notEnd']))
        { // here we got restricted ending
          continue;
        }
        if(isset($rule['before']))
        {  // if this rule need to match some letters before ending
          if(!is_array($rule['before']))
          {
            if($rule['before'] == self::CONSONANT) $rule['before'] = $this->consonants;
            if($rule['before'] == self::VOWEL)     $rule['before'] = $this->vowels;
          }
          $matched_before = false;
          foreach ($rule['before'] as $possible_before) 
          {
            if(mb_strrchr($this->$type, $possible_before . $matched_end) == $possible_before . $matched_end) 
            {
              $matched_before = $possible_before;
              break;
            }
          }
          if(!$matched_before)
          {  // We mast match before. seems like we cant here.
            continue;
          }
        }
    
        // so if we got here this rule fits perfectly!
        $this->matched_ends[$type] = $matched_end;
        return $this->matched_rules[$type] = $id_rule;
      }
    }
    // if we got here - then no rule is fiting our string...
    return false;
  }

  /**
   * This method returns a word in any case that you need
   * 
   * @param String $type
   * @param String $case
   * @return String
   * @throws Exception
   */
  public function getWordByCase($type, $case = '')
  {
    if(!$case)
    { // here we just return nominative
      return $this->$type;
    }
    if(!in_array($case, $this->cases) && $case) 
    {
      throw new Exception("Error at getWordByCase. Wrong case");
    }
    if(empty($this->matched_rules[$type]))
    {
      return $this->$type;
    }
    $id_rule = $this->matched_rules[$type];
    if(isset($this->rules[$id_rule]['cases']))
    { 
      $id_case = array_search($case, $this->cases);
      $new_ending = $this->rules[$id_rule]['cases'][$id_case];
    } else {
      // this word didnt changes
      $new_ending = '';
    }
  
    if($new_ending)
    {
      if(in_array(mb_substr($this->matched_ends[$type],-1), $this->vowels))
      { // if last letter of the word is vowel we replace the ending with new one
        $declensed_word = mb_substr($this->$type, 0, -mb_strlen($this->matched_ends[$type])) . $new_ending;
      } else {
        // here we just add new ending 
        $declensed_word = $this->$type . $new_ending;
      }
    } else {
      $declensed_word = $this->$type;
    }
    return $declensed_word;
  }
  
  /**
   * A simple method for testing
   */
  public static function unitTest()
  {
    $data = [
      ['f', 'Мария', 'Белова', ['Марии Беловой', 'Марие Беловой', 'Марию Белову', 'Марией Беловой', 'Марие Беловой']],
      ['f', 'Юлия', 'Портная', ['Юлии Портной', 'Юлие Портной', 'Юлию Портную', 'Юлией Портной', 'Юлие Портной']],
      ['f', 'Ирина', 'Штерн', ['Ирины Штерн', 'Ирине Штерн', 'Ирину Штерн', 'Ириной Штерн', 'Ирине Штерн']],
      ['f', 'Екатерина', 'Иванькина', ['Екатерины Иванькиной', 'Екатерине Иванькиной', 'Екатерину Иванькину', 'Екатериной Иванькиной', 'Екатерине Иванькиной']],
      ['m', 'Иван', 'Иванов', ['Ивана Иванова', 'Ивану Иванову', 'Ивана Иванова', 'Иваном Ивановым', 'Иване Иванове']],
      ['m', 'Александр', 'Гернович', ['Александра Герновича', 'Александру Герновичу', 'Александра Герновича', 'Александром Герновичем', 'Александре Герновиче']],
      ['m', 'Павел', 'Орловский', ['Павелаe Орловскийого', 'Павелу Орловскийому', 'Павела Орловскийого', 'Павелом Орловскийим', 'Павеле Орловскийом']],
      ['m', 'Федор', 'Туленцев', ['Федораe Туленцева', 'Федору Туленцеву', 'Федора Туленцева', 'Федором Туленцевым', 'Федоре Туленцеве']],
    ];
    $declension = new Declension();
	  $errors = array();
    foreach ($data as $test)
    {
      $result = $declension->setPerson($test[0], $test[1], $test[2])->getAll();
      foreach ($result as &$case) 
      {  // here we just implode result, so test config can be simpler
        $case = implode(' ', $case);      
      }
      if(array_values($result) !== $test[3])
      {
        $errors []= 'at ' . $test[1] . ' ' . $test[2] .'<br>';
      }	  
    }
    if($errors)
    {
      echo "test failed " . implode("\n and ", $errors);
    } else {
      echo 'test passed';
    }
  }
}

?>
