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
    for(var i in data) 
	{ 
      if(current_row <= data[i].toWidth) 
	  {
		Photo.fitRow(row, current_row);
        row = [];
        current_row = row_width;
      }
	  
      data[i].img.width(data[i].toWidth);
	  row.push(data[i]);
	  current_row -= data[i].toWidth + 2;
    }
	if(current_row != 0)
	{
		Photo.fitRow(row,current_row);
	}
	$('.photo-gallery').css('visibility','visible');
  }
  
  this.fitRow = function(row, width_fitting) {
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