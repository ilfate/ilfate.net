/**
 * Created by Ilya RUbinchik on 3/12/14.
 */




function getRandomInt(min, max)
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
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
$('#SnapABug_P input').unbind().bind('click', function(){
    message('Hi this text should be nice printed. Well lets hope so. Boom! This how I want text to flow in super-quest-game.', '#SnapABug_CL', 0);
});


divPrint = function(selector) {
    message($(selector).html(), selector, 0);
}
