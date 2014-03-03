<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-6">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Ilfate</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-6">
                <ul class="nav navbar-nav">
                    <? foreach ($ilfate_menu as $menuElement) { ?>
                    <li <?= isset($menuElement['active'])? 'class="active"':''?>>
                        <a href="<?= Helper::url($menuElement['class'], $menuElement['method'])?>">
                            <?= $menuElement['text']?>
                        </a>
                    </li>
                    <? } ?>
                </ul>
            </div>
        </div>
        <!--
      <? if(FrontController_Auth::isAuth()) { ?>
        <div class="btn-group pull-right">
        <button class="btn dropdown-toggle" data-toggle="dropdown">
          <?=FrontController_Auth::getUser()->name?>
          <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a>Profile</a></li>
            <li><a class="ajax" href="<?=Helper::url('Auth', 'logOut')?>">Log out</a></li>
          </ul>
        </div>
      <? } else { ?>
        <div class="btn-group pull-right">
          <a href="#ilfateModal" data-remote="<?= Helper::urlAjax('Auth', 'logInForm', array('__html'=>true)) ?>" role="button" data-toggle="modal" class="btn btn-primary">Log in</a>
          <a href="#ilfateModal" data-remote="<?= Helper::urlAjax('Auth', 'signUpForm', array('__html'=>true)) ?>" role="button" data-toggle="modal" class="btn btn-success">Sign up</a>
        </div>
     
        
      <? } ?>
      -->
    </div>
</nav>

