$(document).ready(function(){

  $(".btn-slide").click(function(e){
    e.preventDefault();
    $(".signup").slideToggle("slow");
    $(".searchlounges").slideUp("slow");
    $(".signinform").slideUp("slow");
    $(this).toggleClass("active");
  });

  $("a.login").click(function(e){
    e.preventDefault();
    $(".signinform").slideToggle("slow");
    $(".signup").slideUp("slow");
    $(".searchlounges").slideUp("slow");
    $(this).toggleClass("active");
  });

  $("a.homesearch").click(function(e){
    e.preventDefault();
    $(".searchlounges").slideToggle("slow");
    $(".signup").slideUp("slow");
    $(".signinform").slideUp("slow");
    $(this).toggleClass("active");
  });

  $(".closedrops a").click(function(e){
    e.preventDefault();
    $(".signinform").slideUp("slow");
    $(".signup").slideUp("slow");
    $(".searchlounges").slideUp("slow");
    $(this).toggleClass("active");
  });

  $(".img-swap").hover(
    function(){this.src = this.src.replace("_norm","_over");},
    function(){this.src = this.src.replace("_over","_norm");
  }); 

  $('#search').submit(function(e){
    e.preventDefault();
    var form = $(this);
    var results = $('#search-results');
    results.empty();
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize(),
      dataType: 'json',
      success: function(data, textStatus){
        if(data.length == 0){
          var noResultsEl = '<li class="empty">No lounges found matching your search</li>';
          results.append(noResultsEl);
        }
        $.each(data, function(key, val) {
          var el = '<li>';
          el += '<a href="http://' + val.url + '">';
          el += '<img src="' + val.img + '" />';
          el += '</a>';
          el += '<div class="info">';
          el += '<a href="http://' + val.url + '">';
          el += '<span class="name">' + val.name + '</span><br />';
          el += '<span class="url">' + val.url + '</span><br />';
          el += '</a>';
          el += '<span class="privacy">' + val.privacy + '</span>';
          el += '</div>';
          el += '<div class="clear"></div>';
          el += '</li>';
          results.append(el);
        });
      }
    });
  });

});
