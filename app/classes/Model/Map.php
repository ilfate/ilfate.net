<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/*
CREATE  TABLE `ilfate`.`map_p1` (
  `id` INT UNSIGNED NOT NULL ,
  `x` INT UNSIGNED NOT NULL ,
  `y` INT UNSIGNED NOT NULL ,
  `cells` CHAR(48) NOT NULL ,
  `id_biom` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_y_x` (`y` ASC, `x` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
*/



/**
 * model class 
 *
 * @author ilfate
 */
class Model_Map extends Model
{
  protected static $default_name = 'map_p';
  public static $table_name = 'map_p';
  protected static  $planet = 1;

 
  public static function setPlanet($id_planet)
  {
    self::$planet = $id_planet;
    self::$table_name = self::$default_name . self::$planet;
  }

  public static function getChunksById($ids)
  {
    return self::getList(' `id` IN ( ' . implode(',', $ids) . ' ) ');
  }


}

