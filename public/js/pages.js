/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

function Photo() {

  this.openPhoto = function(el) 
  {
    $('#photoModal').modal();
    var img = $(el).children().first().clone().click(Photo.nextPhoto);
    //img.css();
    $('#photoModal .modal-body').html('').append(img);
    $('.current-photo').removeClass('current-photo');
    $(el).parent().addClass('current-photo');
  }

  this.nextPhoto = function() 
  {
    var el = $('.current-photo').next('.photo');
    if(!el[0]) {
      el = $('.photo').first();  
    }
    Photo.setImage(el);
  }

  this.prevPhoto = function() 
  {
    var el = $('.current-photo').prev('.photo');
    if(!el[0]) {
      el = $('.photo').last();  
    }
    Photo.setImage(el);
  }
  
  this.setImage = function(el)
  {
    $('#photoModal .modal-body').html('').append(el.find('img').clone().click(Photo.nextPhoto));
    $('.current-photo').removeClass('current-photo');
    el.addClass('current-photo');
  }
}

Photo = new Photo();