<?php
//This function enqueue scripts
if(!function_exists('wpftl_enqueue_scripts')){
    function wpftl_enqueue_scripts(){
        wp_enqueue_style('wpftl-login-style', '/wp-content/plugins/wp-fast-login/views/assets/css/wpftl-style.css', array(), '1.0.11', 'all');
        wp_enqueue_script('wpftl-login-js2', '/wp-content/plugins/wp-fast-login/views/assets/js/wpftl-login.js', array('jquery'), '1.0.11', true);

        wp_localize_script('wpftl-login-js2', 'login_obj', array(
            'ajax_url' => admin_url("admin-ajax.php"),
            'home_url' => home_url('/'),
            'logout_nonce' => wp_create_nonce('ajax-logout-nonce'),
        ));
    }
}

//this function add create form login
if(!function_exists('wpftl_content_form_login')){
    function wpftl_content_form_login(){
        include plugin_dir_path(__FILE__) . '../views/templates/login/wpftl-form.php';
    }
}

//This function create form new login
if(!function_exists('wpftl_form_login')){
    function wpftl_form_login(){
        $current_user = wp_get_current_user();

        if (is_user_logged_in() === false) {
            //do_action('wpftl-content-form-login');            
            $formHTML = '<form method="post" id="login">
                            <div class="form-control">
                                <input type="text" name="username" id="login_username" placeholder="Login" required>    
                            </div>
                            <div class="form-control">
                                <input type="password" name="password" id="login_password" placeholder="Senha" required>        
                            </div>
                            <div class="form-control">
                                <input type="submit" value="Entrar" id="entrar">    
                            </div>                                   
                        </form>
                        <p id="show-user-error"></p> ';
            return $formHTML;
        }else{          
            return '<span class="current-user-text">Olá, '. $current_user->user_firstname . ' | <a href="#" id="logoutuser">Sair</a></span>';         
        }
    }
}

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