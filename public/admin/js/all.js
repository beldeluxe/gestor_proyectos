$.allJs = function(){
  
  var ie = (function(){
 
      var undef,
          v = 3,
          div = document.createElement('div'),
          all = div.getElementsByTagName('i');
    
      while (
          div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
          all[0]
      );
    
      return v > 4 ? v : undef;
    
  }());
  
  if( ie == '8' && $('.steps').length > 0){
    $('.steps li').append('<span class="before"></span>');
  }

  /* dropdowns, basic, only open close */

  $('.dropdown__selected').on('click', function(e){
    e.preventDefault();
    var $dropdown = $(this).parents('.dropdown');
    $dropdown.toggleClass('active');
  });

    $('.selectize').on('change', function(e){
        e.preventDefault();
        var valueSelected = this.value;
        if (valueSelected){
            window.location.href = valueSelected;
        }
    });

    $('.selectize-input input').on('mousedown', function(e){
        e.preventDefault();
        $(this).parents('.selectize-input').mousedown();
    });

    $('.dropdown').find('.dropdown__options a').on('click', function(){
        var $dropdown = $(this).parents('.dropdown');
        if ($dropdown.parents('.header__user') == null) {
            text = $(this).text();
            $dropdown.find('.dropdown__selected span').text(text);
        }
        $dropdown.removeClass('active');
        $dropdown.addClass('selected');
    });


    // show menu
  $('#show-menu')
    .on('click', function(e){
      e.preventDefault();
      $('.header__mainmenu').show();
      var newHeight = $(window).height();
      $('.main-container').css({
        'height':newHeight+'px',
        'overflow':'hidden'
      });
      $('.header__mainmenu').css({'height':newHeight+'px'});
      if($('.overlay').length <= 0){
        $('body').append('<a class="overlay" href="#"></a>');
      }
    });
    
  // box toggle
  $('.box__toggle')
    .on('click', function(e){
      e.preventDefault();
      var $box = $(this).parents('.box');
      $box.toggleClass('active inactive');
    });
    
  // accordion toggle
  $('.accordion__toggle')
    .on('click', function(e){
      e.preventDefault();
      var $accordion = $(this).parents('.accordion');
      $accordion.toggleClass('active');
      $(this).find('span').toggleClass('hide');
    });
    
  // hide menu
  $('body')
    .on('click', '.overlay', function(e){
      e.preventDefault();
      $('.header__mainmenu').hide();
      $('.overlay').remove();
      $('.main-container').css({
        'height': 'auto',
        'overflow':'auto'
      });
    });
  
  // modals
  $(".popup")
    .colorbox({
      inline: true
    });
    
  // login height
  if($('.login .col-sm-7').length > 0){
      return;
    $('.login .col-sm-7').css('height', $('.login .col-sm-5').outerHeight());
  }
  
};

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 1) {
            n = Number(arguments[1]);
            if (n != n) { // para verificar si es NaN
                n = 0;
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= len) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k;
            }
        }
        return -1;
    }
}

function submitFormPartner(){

    if ($("#partnerForm").valid()) {
        $("#partnerForm").submit();
    }
}

function submitFormUser(){

    $("#new-form").submit();


    /*if ($("#documentationUserForm").valid()) {
        var doc1 = $("#documentationUser1").val();
        var doc2 = $("#documentationUser2").val();
        var docUpload1 = $("#documentationForm1").val();
        var docUpload2 = $("#documentationForm2").val();

        if (docUpload1!='' || docUpload2!='') {
            if (doc1 != '' || doc2 != '') {
                if (confirm('Se sobreescribirá la documentación existente. ¿Desea continuar? ')) {
                    $("#documentationUserForm").submit();
                }
            }else{
                $("#documentationUserForm").submit();
            }

        }else
            alert("No has realizado ningún cambio");

    }*/
}

function submitNewFormUser(){

    $("#new-form").submit();

}
