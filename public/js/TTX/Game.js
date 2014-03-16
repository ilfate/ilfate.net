/**
 * Created by ilfate on 3/16/14.
 */

/**
 * Created by Ilya RUbinchik on 3/12/14.
 */
function error(message)
{
  info(message);
}

function getRandomInt(min, max)
{
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function t(key)
{
  var translations = {
    'ru': {
      'lang':'язык'
    },
    'en': {
      'lang':'lang'
    }
  };
  lang = 'ru';

  if (!translations[lang][key]) {
    return key;
  }
  return translations[lang][key];
}



var message = function(text, selector, lengthPrinted) {

  delay = 50;
  delay += getRandomInt(-8, 7);
  if (typeof text == 'string') {
    $(selector).html(text.substr(0, lengthPrinted) + '|');
    if (text.substr(lengthPrinted - 1, 1) == ' ') {
      delay *= 2;
    } else if (text.substr(lengthPrinted - 1, 1) == '.') {
      delay *= 4;
    } else if (text.substr(lengthPrinted - 1, 1) == '?') {
      delay *= 6;
    } else if (text.substr(lengthPrinted - 1, 1) == '!') {
      delay *= 8;
    }

    lengthPrinted++;
    if (text.length < lengthPrinted) {
      $(selector).html(text);
      return false;
    }
    setTimeout(function() {message(text, selector, lengthPrinted)}, delay);
  }
}
//$('#SnapABug_P input').unbind().bind('click', function(){
//    message('Hi this text should be nice printed. Well lets hope so. Boom! This how I want text to flow in super-quest-game.', '#SnapABug_CL', 0);
//});
//
//
//divPrint = function(selector) {
//    message($(selector).html(), selector, 0);
//}



var Action = function(key, game)
{
  this.game = game;
  this.key = key;
  this.active = false;
  this.config = {
    'open_solar_battery': ['ship', 1000],
    'close_solar_battery': ['ship', 1000]
  };

  if (!this.config[key]) {
    error('Action "' + key + '" do not have configuration');
  }

  this.el = $('<button type="button" class="btn btn-default"></button>');
  this.el.attr('id', key);
  this.el.html(t(key))
  $('#' + this.config[key][0] + ' .actions').append(this.el);
  this.el.addClass('hide');

  this.init = function() {
    this.el.bind('click', jQuery.proxy(this, 'run'));
  }

  this.run = function()
  {
    if (!this.runCheck()) {
      return false;
    }
    this.beforeRun();
    setTimeout(jQuery.proxy(this, 'afterRun'), this.config[this.key][1]);
    info('Run action "' + this.key + '"');
  }

  this.beforeRun = function()
  {
    switch (this.key) {
      case 'open_solar_battery' :
        this.game.deactivateAction('open_solar_battery');
        break;
      case 'close_solar_battery' :
        this.game.deactivateAction('close_solar_battery');
      break;
      default :
        error('no beforeRun for "' + this.key + '"');
        break;
    }
  }

  this.afterRun = function()
  {
    switch (this.key) {
      case 'open_solar_battery' :
        this.game.activateAction('close_solar_battery');
        break;
      case 'close_solar_battery' :
        this.game.activateAction('open_solar_battery');
        break;
      default :
        error('no afterRun for "' + this.key + '"');
        break;
    }
  }

  this.runCheck = function()
  {
    if (!this.active) {
      error('some one tries to run disabled action "' + this.key + '". We should report this. Yes we totally do! Report sent! Hackers well be found and punished! They definitely will be!');
      return false;
    }
    return true;
  }

  this.activate = function()
  {
    this.el.removeClass('hide');
    this.active = true;
  }
  this.deactivate = function()
  {
    this.el.addClass('hide');
    this.active = false;
  }
}

var Game = function()
{
  this.actions = {};
  this.ship = new Ship(this);

  this.init = function() {
    var savedData = $('#savedGame').val();
    if (savedData) {
      this.load(savedData);
    } else {
      info('New game init');
      this.newGame();
    }
  }

  this.newGame = function()
  {
    // Set up new game screen
    this.ship.activateSystem('solar_batteries');
    this.activateAction('open_solar_battery');
  }

  this.getAction = function(key)
  {
    if (!this.actions[key]) {
      error('There is no action ' + key + ' in the game.');
      return false;
    }
    return this.actions[key];
  }

  this.activateAction = function(key)
  {
    info('Activate action "' + key + '"');
    if (!this.actions[key]) {
      this.actions[key] = new Action(key, this);
      this.actions[key].init();
    }
    this.actions[key].activate();
  }
  this.deactivateAction = function(key)
  {
    if (!this.actions[key]) {
      this.actions[key] = new Action(key, this);
    }
    this.actions[key].deactivate();
  }

  /**
   *
   * @param data
   */
  this.load = function(data) {

  }
}

$(document).ready(function()
{
  var game = new Game();
  game.init();
});

