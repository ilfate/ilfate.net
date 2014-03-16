<?php
/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

/**
 * Description of Game
 *
 * @author ilfate
 */
class Controller_Game_Main extends Controller
{

    /**
     *
     * @return type
     */
    public function index()
    {
        return array(
            'mode' => Request::EXECUTE_MODE_HTTP,
            'tpl'  => 'Game/index.tpl'
        );
    }

    public function gameBlank()
    {
        FrontController_Sidebar::addSideBar('Game_Main', 'gameBlankinfo');
        Js::add(Js::C_ONLOAD, 'CanvasActions.init()');
        return array(
            'layout' => array('html.tpl', 'Game/headBlank.tpl', 'layout.tpl'),
            'mode'   => Request::EXECUTE_MODE_HTTP,
            'tpl'    => 'Game/gameBlank.tpl'
        );
    }

    public function gameBlankinfo()
    {
        return array(
            'tpl' => 'Game/gameBlankInfo.tpl'
        );
    }

    /**
     *
     * @return type
     */
    public function gameWindow()
    {
        return array(
            'mode' => Request::EXECUTE_MODE_HTTP,
            'tpl'  => 'Game/gameWindow.tpl'
        );
    }

    public function jsGame()
    {
        FrontController_Sidebar::addSideBar('Game_Main', 'gameBlankinfo');
        //Js::add(Js::C_ONLOAD, 'CanvasActions.init()');
        return array(
            'layout' => array('html.tpl', 'TTX/head.tpl', 'TTX/layout.tpl'),
            'mode'   => Request::EXECUTE_MODE_HTTP,
            'tpl'    => 'TTX/index.tpl'
        );
    }
}
