<?php
//This function enqueue scripts
if(!function_exists('wpftl_enqueue_scripts')){
    function wpftl_enqueue_scripts(){

        $plugin_uri = str_replace('/includes', '', plugin_dir_url(__FILE__));

        wp_enqueue_style('wpftl-login-style', $plugin_uri . 'assets/css/wpftl-style.css', array(), '1.0.14', 'all');
        wp_enqueue_script('wpftl-login-js2',  $plugin_uri . 'assets/js/wpftl-login.js', array('jquery'), '1.0.14', true);

        wp_localize_script('wpftl-login-js2', 'login_obj', array(
            'ajax_url' => admin_url("admin-ajax.php"),
            'home_url' => home_url('/'),
            'logout_nonce' => wp_create_nonce('ajax-logout-nonce'),
        ));
    }
}

add_action('wp_enqueue_scripts', 'wpftl_enqueue_scripts');

//This function create form new login
if(!function_exists('wpftl_form_login')){
    function wpftl_form_login(){
        $current_user = wp_get_current_user();

        if (is_user_logged_in() === false) {
          
            $formLo = '
            
            <form method="post" id="login">              
                <input class="input-f" type="text" name="username" id="login_username" placeholder="Login" required/>    
                <input class="input-f" type="password" name="password" id="login_password" placeholder="Senha" required/>                       
                <input class="input-f login-f" type="submit" value="Entrar" id="entrar"/>                                                    
            </form>
            <p id="show-user-error"></p>
            
            ';

            return $formLo;
           
        }else{          
            return '<span class="current-user-text">Olá, '. $current_user->user_firstname . ' | <a href="#" id="logoutuser">Sair</a></span>';         
        }

    }
}

add_shortcode('wpftl-form-login', 'wpftl_form_login');

//this function is for login
if(!function_exists('wpftl_login')){
    function wpftl_login(){

        $array = array('status' => 1);
    
        if(empty($_POST['username']) || empty($_POST['password'])){
            wp_send_json($array);
        }
    
        $username = sanitize_user($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
    
        if(!username_exists($username)){
            wp_send_json($array);
        }
    
        $userdata = get_user_by('login', $username);
    
        $creds = array();
        $creds['user_login'] = $userdata->user_login;
        $creds['user_password'] = $password;
        $creds['remember'] = true;
    
    
        $user_signon = wp_signon( $creds, is_ssl() ? true : false );
    
        if(is_wp_error($user_signon)){
            wp_send_json($array);
        }
    
        $array = array('status' => 2);
    
        wp_send_json($array);
    
    }
    
}

//Login
add_action('wp_ajax_nopriv_wpftl_login','wpftl_login');
add_action('wp_ajax_wpftl_login','wpftl_login');

//this function is for logout
if(!function_exists('wpftl_logout')){
    function wpftl_logout(){
        check_ajax_referer( 'ajax-logout-nonce', 'ajaxsecurity' );
        wp_clear_auth_cookie();
        wp_logout();
        ob_clean();
        wp_die();
    }
}

//Logout
add_action( 'wp_ajax_wpftl_logout', 'wpftl_logout' );
add_action( 'wp_ajax_nopriv_wpftl_logout', 'wpftl_logout' );
