$(document).ready(function(){

    $(document).on('click', '.change_page', function(){
        const $url = $(this).attr('data-url');
        const $f_url = 'test2.php/?'+$url;
        $.ajax({
            type: "POST",
            url: $f_url,
            success: function(data){
                $(".lenta_news").html($(data).find('.lenta_news').html());
            }
        });    

    });

});