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
  
  this.createRows = function(row_width, row_height) {
    data = [];
    
    $('.photo').each(function(){
      var img = $(this).find('img');
      var width = img.width();
      var height = img.height();
      var toWidth = width/(height/row_height);
      data.push({'width' : width, 'height' : height, k : width/height, 'toWidth':toWidth, 'img' : img})
    });
    var current_row = row_width;
    var row = [];
    for(var i in data) {
      
      if(current_row > data[i].toWidth) {
        
      } else {
        info(current_row);
        //data[i].img.width(current_row);//data[i].toWidth+
        for(var i2 in row) {
          if(row[i2].k < 1) {
            var h_width = parseInt(current_row / 2);
            row[i2].img.width(row[i2].toWidth + h_width);
            current_row -= h_width;
          }
        }
        row = [];
        current_row = row_width;
      }
      data[i].img.width(data[i].toWidth);
      row.push(data[i]);
      current_row -= data[i].toWidth;
    }
    info(data);
    
  }
}

Photo = new Photo();