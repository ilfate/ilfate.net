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

var System = function(systemKey, ship)
{
  this.ship = ship;
  this.systemKey = systemKey;
}

var Ship = function(game)
{
  this.game = game;
  this.systems = {};

  this.init = function()
  {

  }

  this.getSystem = function(systemKey)
  {
    if (!this.systems[systemKey]) {
      error('There is no system ' + systemKey + ' on the ship.');
      return false;
    }
    return this.systems[systemKey];
  }
}

var Action = function(key, type, game)
{
  this.game = game;
  this.key = key;
  this.active = false;
  this.config = {
    'open_solar_battery': ['ship'],
    'close_solar_battery': ['ship']
  };

  if (!this.config[key]) {
    error('Action "' + key + '" do not have configuration');
  }

  this.el = $('<button type="button" class="btn btn-default"></button>');
  this.el.attr('id', key);
  this.el.html(t(key))
  $('#' + this.config[key][0] + ' .actions').append(this.el);

  this.run = function()
  {
    if (!this.active) {
      error('some one tries to run disabled action "' + key + '". We should report this. Yes we totally do! Report sent! Hackers well be found and punished! They definitely will be!');
      return false;
    }
    switch (key) {
      case 'open_solar_battery' :
        this.game.activateAction('close_solar_battery');
        this.game.deactivateAction('open_solar_battery');
        break;
      case 'close_solar_battery' :
        this.game.activateAction('open_solar_battery');
        this.game.deactivateAction('close_solar_battery');
        break;
    }
  }

  this.activate = function()
  {
    this.active = true;
  }
  this.deactivate = function()
  {
    this.active = false;
  }
}

var Game = function()
{
  this.actions = {};

  this.init = function() {
    var savedData = $('#savedGame').val();
    if (savedData) {
      this.load(savedData);
    } else {
      this.newGame();
    }
  }

  this.newGame = function()
  {
    // Set up new game screen
    this.activateAction('open_solar_battery')
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
    if (!this.actions[key]) {
      this.actions[key] = new Action(key, this);
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

$(document).ready(function(){
  var game = new Game();
  game.init();
});

