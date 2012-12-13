/**
 * ILFATE PHP ENGINE
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2012
 */

function Photo() {

  this.openPhoto = function(el) 
  {
    $('#photoModal').modal();
    var img = $('<img src="'+$(el).children().first().attr('src')+'" />').click(Photo.nextPhoto);
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
    $('#photoModal .modal-body').html('').append($('<img src="'+el.find('img').attr('src')+'" />').click(Photo.nextPhoto));
    $('.current-photo').removeClass('current-photo');
    el.addClass('current-photo');
  }
  
  this.createRows = function(row_width, row_height, is_big) 
  {
    data = [];
    var margin = 2;
    $('.photo').each(function(){
      var img = $(this).find('img');
      var width = img.width();
      var height = img.height();
      var toWidth = width/(height/row_height);
      data.push({'width' : width, 'height' : height, k : width/height, 'toWidth':toWidth, 'img' : img})
    });
    var current_row = row_width;
    var row = [];
    var row_num = 1;
    var big_width = 0;
    var max_elements = 0;
    var elements_in_row = 0;
    if(is_big)
    {
      var rand = Math.floor((Math.random() * data.length));
      big_width = data[rand].width/(data[rand].height/((row_height*2)+margin));
      data[rand].img.width(big_width);
      var rand_photo = data[rand].img.parents('div').first();
      rand_photo.prependTo(rand_photo.parent()).height((row_height * 2) + margin);
      current_row -= big_width + margin;
    }
    for(var i in data) 
    {
      if(current_row <= data[i].toWidth) 
      {
        Photo.fitRow(row, current_row);
        row = [];
        current_row = row_width;
        
        if(is_big && row_num <= 2) { elements_in_row++; }
        if(elements_in_row > max_elements) { max_elements = elements_in_row; }
        elements_in_row = 0;
        row_num++;
        if(is_big && row_num == 2)
        {
          current_row -= big_width + margin;
        }
      }
      if(is_big && rand == i) 
      {
        
      } else {
        data[i].img.width(data[i].toWidth);
        row.push(data[i]);
        current_row -= data[i].toWidth + margin;
        elements_in_row++;
      }
      
    }
    if(current_row != 0)
    {
      Photo.fitRow(row,current_row);
    }
    $('.photo-gallery').css({visibility:'visible'}).width(row_width + (max_elements * margin));
  }
  
  this.fitRow = function(row, width_fitting) 
  {
    var row_fit_arr = [];
    var all_fit = 0;
    for(var i = 0; i < row.length; i++)
    {
      var can_fit = row[i].width-row[i].toWidth;
      row_fit_arr[i] = can_fit;
      all_fit += can_fit;
    }
    for(var i = 0; i < row.length; i++)
    {
      var avr = ( row_fit_arr[i] / all_fit ) * width_fitting;
      row[i].img.width(row[i].toWidth + avr);
    }
  }
}

Photo = new Photo();