var Ship = function (game) {
    this.game = game;
    this.energy = new Energy(this);
    this.systems = {};

    this.init = function (data) {
        if (!data) {
            // it is a new game
            this.installSystem('solar_batteries');
            this.installSystem('RTG'); //Radioisotope thermoelectric generator
            this.installSystem('TTX-800');
            this.installSystem('star_tracker'); // Звёздный датчик

            this.energy.limit = 5000;
            this.energy.current = 250;
        }
    }

    this.tik = function () {
        this.energy.tik();
    }

    this.getSystem = function (systemKey) {
        if (!this.systems[systemKey]) {
            error('There is no system ' + systemKey + ' on the ship.');
            return false;
        }
        return this.systems[systemKey];
    }
    this.hasSystem = function(key)
    {
        if (this.systems[key]) {
            return true;
        }
        return false;
    }
    this.isActiveSystem = function(key)
    {
        if (this.systems[key] && this.systems[key].active) {
            return true;
        }
        return false;
    }

    this.installSystem = function (key) {
        this.systems[key] = new System(key, this);
        this.systems[key].install();
    }

    this.activateSystem = function (key) {
        if (!this.systems[key]) {
            this.installSystem(key);
        }
        this.systems[key].activate();
    }
    this.deactivateSystem = function (key) {
        this.getSystem(key).deactivate();
    }

    this.systemsShutDown = function () {
        for (var key in this.systems) {
            this.deactivateSystem(key);
        }
    }
}

var System = function (systemKey, ship) {
    this.ship = ship;
    this.key = systemKey;
    this.active = false;
    this.energyOutcome = 0;
    this.energyIncome = 0;
    this.problems = {};

    this.install = function () {
        switch (this.key) {
            case 'solar_batteries' :
                this.energyIncome = 20;
                break;
            case 'RTG' : //Radioisotope thermoelectric generator
                this.energyIncome = 10;
                break;
            case 'life_support_system' :
                this.energyOutcome = 10;
                break;
            case 'TTX-800':
                this.energyOutcome = 1;
                break;
            case 'TTX_diagnostic':
                this.energyOutcome = 50;
                break;
            default :
                error('System ' + this.key + ' is do not have install action');
                break;
        }
    }

    this.activate = function () {
        if (this.active) {
            info('Attempt to activate already active system');
            return false;
        }
        this.active = true;
        this.ship.energy.income += this.energyIncome;
        this.ship.energy.outcome += this.energyOutcome;
    }
    this.deactivate = function () {
        if (!this.active) {
            info('Attempt to deactivate already inactive system');
            return false;
        }
        this.active = false;
        this.ship.energy.income -= this.energyIncome;
        this.ship.energy.outcome -= this.energyOutcome;
    }
    this.addProblem = function(key)
    {
        if (this.problems[key]) {
            error('we cant have two same problems "' + key + '" in one system "' + this.key + '"');
        }
        this.problems[key] = new Problem(key, this);
    }
}

var Energy = function (ship) {
    this.ship = ship;
    this.limit = 0;
    this.current = 0;
    this.lastEnergy = 0;
    this.income = 0;
    this.outcome = 0;

    this.tik = function () {
        if (this.outcome == this.income && this.current == this.lastEnergy) {
            return;
        }

        this.current -= this.outcome;
        this.current += this.income;

        if (this.current < 0) {
            this.current = 0;
            this.ship.systemsShutDown();
            info('Ship is out of energy. All systems was shut down');
        }

        if (this.current > this.limit) {
            this.current = this.limit;
        }
        this.lastEnergy = this.current;
        $('#energyBar').width(Math.round(this.current * 100 / this.limit) + '%');
    }
}

var Problem = function (key, system) {
    this.key  = key;
    this.system = system;
    this.needDiagnostic = true;



}