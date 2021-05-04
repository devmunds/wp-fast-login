// Login
jQuery('#login').on('submit', function(e){
    e.preventDefault();

    var $ = jQuery;

    var form = {
        'action': 'wpftl_login',
        'email': $('#login_email').val(),
        'password': $('#login_password').val(),
    }

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: login_obj.ajax_url,
        data: form,
        success: function(json){
            if(json.status == 2){
                setTimeout(function(){
                    location.reload();
                  }, 2000);
                  
            }else{
                jQuery("#login").html('<span class="current-user-text">Usuário / senha invalido</span>');
            }
        }
    });

});