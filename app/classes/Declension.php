<?php

/**
 * Class for name declension in russian
 *
 * @author Ilya Rubinchik
 */
class Declension
{
  protected $rules = [
    ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => self::CONSONANT, 'cases' => ['', '', '', '', '']],
    ['sex' => [self::FEMALE], 'type' => [self::NAME], 'end' => self::CONSONANT, 'notEnd' => ['ь'], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ого', 'яго', 'ово', 'ых', 'их'], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['иа', 'ия'], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ко'], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME, self::NAME], 'end' => self::VOWEL, 'notEnd' => ['а', 'я'], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['х', 'ц'], 'cases' => ['', '', '', '', '']],
	  
    ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['ь'], 'cases' => ['я', 'ю', 'я', 'ем', 'е']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['ий', 'ой'], 'cases' => ['ого', 'ому', 'ого', 'им', 'ом']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['й'], 'cases' => ['я', 'ю', 'я', 'ем', 'е']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['б', 'г', 'д', 'з', 'к', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ч'], 'before' => ['а'], 
	  'cases' => ['а', 'у', 'а', 'ом', 'е']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['ж', 'ч', 'ш', 'щ', 'ц'], 'cases' => ['а', 'у', 'а', 'ем', 'е']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['в', 'л'], 'cases' => ['а', 'у', 'а', 'ым', 'е']],
    ['sex' => [self::MALE], 'type' => [self::NAME], 'end' => self::CONSONANT, 'cases' => ['а', 'у', 'а', 'ом', 'е']],
    ['sex' => [self::FEMALE], 'type' => [self::NAME], 'end' => ['ь'], 'cases' => ['и', 'и', 'ь', 'ь', 'и']],
    ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ая'], 'cases' => ['ой', 'ой', 'ую', 'ой', 'ой']],
    ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => ['а'], 'before' => self::CONSONANT, 'cases' => ['ой', 'ой', 'у', 'ой', 'ой']],
    ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => ['я'], 'before' => self::CONSONANT, 'cases' => ['и', 'е', 'ю', 'ой', 'е']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::NAME], 'end' => ['а'], 'cases' => ['ы', 'е', 'у', 'ой', 'е']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::NAME], 'end' => ['я'], 'cases' => ['и', 'е', 'ю', 'ей', 'е']],
    ['sex' => [], 'type' => [], 'end' => [], 'cases' => ['', '', '', '', '']],
  ];
  protected $possibleSex = [
	  0 => [0, 'm', 'м', 'man', 'male', 'муж'],
	  1 => [1, 'f', 'ж', 'women', 'female', 'жен']
  ];
  protected $vowels = ['а', 'у', 'о', 'ы', 'и', 'э', 'я', 'ю', 'ё', 'е'];
  protected $consonants = ['б', 'в', 'г', 'д', 'ж', 'з', 'й', 'к', 'л', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ь'];
  protected $cases = [
	  'genitive',      // 'Родительный'
	  'dative',        // 'Дательный'
	  'accusative',    // 'Винительный'
	  'ablative',      // 'Творительный'
	  'prepositional', // 'Предложный'
  ];
  
  protected $exceptionLib = [];
  
  const MALE      = 0;
  const FEMALE    = 0;
  const NAME      = 'name';
  const SURNAME   = 'surname';
  const VOWEL     = 'vowel';
  const CONSONANT = 'consonant';
  
  protected $name;
  protected $surname;
  protected $sex;
  protected $matched_rules;

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
  }
  
  public function getAll()
  {
	  $return = [];
    $this->findRule(self::NAME);
    $this->findRule(self::SURNAME);
    foreach ($this->cases as $case) {
      $return[$case] = $this->getWordByCase(self::NAME, $case) . ' ' . $this->getWordByCase(self::SURNAME, $case);
    }
    return $return;
  }
  
  // this function defines witch rule we are going to use
  protected function findRule($type)
  {
	  if(!$this->$type)
    {  // it is just empty.
      return false;
    }
    // ok lets try to find rule
    foreach ($this->rules as $id_rule => $rule) 
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
        if(strrchr($this->$type, $possible_end) == $possible_end) 
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
        {
          if(!is_array($rule['before']))
          {
            if($rule['before'] == self::CONSONANT) $rule['before'] = $this->consonants;
            if($rule['before'] == self::VOWEL)     $rule['before'] = $this->vowels;
          }
          $matched_before = false;
          foreach ($rule['before'] as $possible_before) 
          {
            if(strrchr($this->$type, $possible_before . $matched_end) == $possible_before . $possible_end) 
            {
              $matched_before = $possible_before;
            }
          }
          if(!$matched_before)
          {  // We mast match before. seems like we cant here.
            continue;
          }
        }
        // so if we got here this rule fits perfectly!
        return $this->matched_rules[$type] = $id_rule;
      }
    }
    // if we got here no rule fits our string...
    return false;
  }

  protected function getWordByCase($type, $case)
  {
    if(!in_array($case, $this->cases)) 
    {
      throw new Exception("Error at getWordByCase. Wrong case");
    }
    if(empty($this->matched_rules[$type]))
    {
      return $this->$type;
    }
    $new_ending = $this->rules[$this->matched_rules[$type]]['cases'][array_search($case, $this->cases)];
    return $this->$type . $new_ending . $this->matched_rules[$type];
  }
}

?>
