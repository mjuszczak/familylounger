$(document).ready(function(){

  /* Close button on alerts */
  $('#messages a.close').each(function() {
    $(this).click(function(e){
      e.preventDefault();
      $(this).parent('li').css('display','none');
    });
  });

  /* Ajax based forms */
  $('form.ajax').each(function(){
      $(this).submit(function(e){
          e.preventDefault();
          var form = $(this);
          var notices = form.find('.notices');
          notices.empty();
          $.ajax({
              type: ((form.attr('method') === undefined || form.attr('method').toLowerCase() == 'post') ? 'post': 'get'),
              url: form.attr('action'),
              data: form.serialize(),
              dataType: 'json',
              success: function(data, textStatus){
                  console.log(data);
                  if(data.redirectUri != false){
                      window.location.href = data.redirectUri;
                  }
                  else {
                      if(data.message != false){
                          var noticeElement = '<li class="' + (data.isError ? 'error' : 'success') + '">' + data.message + '</li>';
                          notices.append(noticeElement);
                      }
                      if(form.hasClass('reset-on-success')){
                          form[0].reset();
                      }
                  }
              }
          })
          .error(function(jqXHR,textStatus){
              console.log(textStatus);
          });
      }); 
  });

  /* Form cancel buttons */
  $('.button.cancel').each(function(){
    $(this).click(function(e){
      if($(this).attr('data-url') === undefined){
        window.history.back();
      }
      else {
        window.location = $(this).attr('data-url');
      }
    });
  });

  /* Confirm functionality for destructive actions */
  $('.confirm-action').each(function(){
    $(this).click(function(e){
      e.preventDefault();
      var confirmUrl = $(this).attr('data-confirm-url');
      var dialog = '<div class="modal" title="' + $(this).attr('data-title') + '">' + $(this).attr('data-msg') + '</div>';
      $(dialog).appendTo($('body')).dialog({
        modal: true,
        resizable: false,
        buttons: {
          'Confirm': function() {
            window.location = confirmUrl;
          },
          'Cancel': function() {
            $(this).dialog("close"); 
          }
        }
      });
    });
  });

  /* Datetime picker element */
  $('.datetimepicker').each(function(){
    $(this).datetimepicker({
      format: $(this).attr('data-format') === undefined ? 'm/d/Y H:i' : $(this).attr('data-format'),
      formatTime: $(this).attr('data-formatTime') === undefined ? 'H:i' : $(this).attr('data-formatTime'),
      inline: $(this).attr('data-inline') === undefined ? false : $(this).attr('data-inline') == 1,
      timepicker: $(this).attr('data-timepicker') === undefined ? true : $(this).attr('data-timepicker') == 1,
      step: $(this).attr('data-step') === undefined ? '60' : $(this).attr('data-step'),
      hours12: $(this).attr('data-hours12') === undefined ? false : $(this).attr('data-hours12') == 1
    });
  });

  /* Modal message */
  $('.message').each(function(){
    $(this).click(function(e){
      e.preventDefault();
      var dialog = '<div class="modal" title="' + $(this).attr('data-title') + '">' + $(this).attr('data-msg') + '</div>';
      $(dialog).appendTo($('body')).dialog({
        modal: true,
        resizable: false,
      });
    });
  });

});
