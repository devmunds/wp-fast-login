// Login
jQuery('#login').on('submit', function(e){
    e.preventDefault();

    var $ = jQuery;

    var form = {
        'action': 'wpftl_login',
        'username': $('#login_username').val(),
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
                  }, 1000);
                  
            }else{
                jQuery("#show-user-error").html('Usuário / senha invalido');
            }
        }
    });

});

//Logout
jQuery('#logoutuser').on('click', function(e){
    e.preventDefault();

    var form = {
        'action': 'wpftl_logout',
        'ajaxsecurity' : login_obj.logout_nonce,
    }

    jQuery.ajax({
        type: 'POST',
        //dataType: 'json',
        url: login_obj.ajax_url,
        data: form,
        success: function(r){
            setTimeout(function(){
                location.reload();
            }, 1000);              
        }
    });

});