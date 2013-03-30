<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/*
'CREATE TABLE `unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_planet` int(11) NOT NULL DEFAULT ''0'',
  `x` int(11) NOT NULL DEFAULT ''0'',
  `y` int(11) NOT NULL DEFAULT ''0'',
  `id_type` int(11) NOT NULL DEFAULT ''1'',
  `energy` int(11) NOT NULL DEFAULT ''0'',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8'
*/



/**
 * model class 
 *
 * @author ilfate
 */
class Model_Unit extends Model
{

  public static $table_name = 'unit';

  public static function getUserUnits($user)
  {
    return self::getList(' `id_user` = ? ', [$user]);
  }

  public static function createUnit($unit = array())
  {
    if (!$unit) {
      $unit = [
        'id_user' => FrontController_Auth::get('id'),
      ];
    }
    return self::insert($unit);
  }

  public static function getActiveUnit($id_user)
  {
    return self::getRecord(" id_user = ? AND active = ?", array($id_user, 1));
  }

  public static function setActivity($id_unit, $activity = 1)
  {
    self::update(array('active' => $activity), array('id' => $id_unit));
  }

  public static function update($id_unit, $set)
  {
    parent::update($set, array('id' => $id_unit));
  }
}

