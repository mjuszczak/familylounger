$(document).ready(function() {
  $('.task input').on('click', function(e){
    e.preventDefault();
    var checkbox = $(this);
    var id = $(this).attr('data-id');
    $.ajax({
      type: 'POST',
      url: '/tasks/toggle.json',
      dataType: 'json',
      data: {
        id: id
      }
    }).success(function(data) {
      var completedOn = '';
      checkbox.prop('checked', data.isCompleted);
      if(data.isCompleted) {
        var completedOn = 'Completed ' + data.completedOn;
      }
      checkbox.parents('.task').find('.completed').html(completedOn);
    });
  });
});
