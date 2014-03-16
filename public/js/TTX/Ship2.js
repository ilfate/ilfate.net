

var System = function(systemKey, ship)
{
  this.ship = ship;
  this.systemKey = systemKey;
  this.active = false;
  this.energyUsage = 0;
  this.energyIncome = 0;

  this.install = function() {
    switch (this.systemKey) {
      case 'solar_batteries' :
        this.energyIncome = 10;
        break;
      default :
        error('System ' + this.systemKey + ' is do not have install action');
        break;
    }
  }

  this.activate = function()
  {
    if (this.active) {
      info('Attempt to activate already active system');
      return false;
    }
    this.active = true;
    this.ship.energy.income += this.energyIncome;
    this.ship.energy.income -= this.energyUsage;
  }
  this.deactivate = function()
  {
    if (!this.active) {
      info('Attempt to deactivate already inactive system');
      return false;
    }
    this.active = false;
    this.ship.energy.income -= this.energyIncome;
    this.ship.energy.income += this.energyUsage;
  }
}

var Ship = function(game)
{
  this.game = game;
  this.energy = new Energy(this);
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

  this.activateSystem = function(key)
  {
    if (!this.systems[key]) {
      this.systems[key] = new System(key, this);
    }
    this.systems[key].activate();
  }
  this.deactivateSystem = function(key)
  {
    this.getSystem(key).deactivate();
  }
}

var Energy = function(ship)
{
  this.ship = ship;
  this.limit = 0;
  this.current = 0;
  this.income = 0;
  this.outcome = 0;
  this.tik = 1000;

}