<?php

/**
 * Class for name declension in russian
 *
 * @author Ilya Rubinchik
 */
class Declension
{
  protected $rules = [
    ['sex' => [self::FEMALE], 'type' => [self::SURNAME], 'end' => [self::CONSONANT], 'notEnt' => [], 'before' => [], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::FEMALE], 'type' => [self::NAME], 'end' => [self::CONSONANT], 'notEnt' => ['ь'], 'before' => [], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ого', 'яго', 'ово', 'ых', 'их'], 'notEnt' => [], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['иа', 'ия'], 'notEnt' => [], 'before' => [], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME], 'end' => ['ко'], 'notEnt' => [], 'before' => [], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE, self::FEMALE], 'type' => [self::SURNAME, self::NAME], 'end' => [self::VOWEL], 'notEnt' => ['а', 'я'], 'cases' => ['', '', '', '', '']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['х', 'ц'], 'notEnt' => [], 'before' => [], 'cases' => ['', '', '', '', '']],
	  
    ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['ь'], 'notEnt' => [], 'before' => [], 'cases' => ['я', 'ю', 'я', 'ем', 'е']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['ий', 'ой'], 'notEnt' => [], 'before' => [], 'cases' => ['ого', 'ому', 'ого', 'им', 'ом']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME, self::NAME], 'end' => ['й'], 'notEnt' => [], 'before' => [], 'cases' => ['я', 'ю', 'я', 'ем', 'е']],
    ['sex' => [self::MALE], 'type' => [self::SURNAME], 'end' => ['б', 'г', 'д', 'з', 'к', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ч'], 'before' => ['а'], 
	  'cases' => ['а', 'у', 'а', 'ом', 'е']],
    ['sex' => [self::SURNAME], 'type' => [self::SURNAME, self::NAME], 'end' => ['ж', 'ч', 'ш', 'щ', 'ц'], 'cases' => ['а', 'у', 'а', 'ем', 'е']],
    ['sex' => [], 'type' => [], 'end' => [], 'notEnt' => [], 'before' => [], 'cases' => ['', '', '', '', '']],
    ['sex' => [], 'type' => [], 'end' => [], 'notEnt' => [], 'before' => [], 'cases' => ['', '', '', '', '']],
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
	  
  }
  
  protected function findRule($type)
  {
	  
  }
}

?>
