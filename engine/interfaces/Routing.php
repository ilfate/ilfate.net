<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of Routing
 *
 * @author ilfate
 */
interface CoreInterfaceRouting 
{
  // init Routing using Request data (that is a-a-a-all you need...)
  public function __construct(CoreInterfaceRequest $request);
  
  // return class name in wich action shuld take place
  public function getClass();
  
  public function getPrefixedClass();
  
  public function getMethod();
  
  public function execute();
  
  public function getUrl($class, $method);
}
