<?php
//This function enqueue scripts
if(!function_exists('wpftl_enqueue_scripts')){
    function wpftl_enqueue_scripts(){
        wp_enqueue_style('wpftl-login-style', '/wp-content/plugins/wp-fast-login/views/assets/css/wpftl-style.css', array(), '1.0.8', 'all');
        wp_enqueue_script('wpftl-login', '/wp-content/plugins/wp-fast-login/views/assets/js/wpftl-login.js', __FILE__,array('jquery'),'1.0.2', true);

        wp_localize_script('wpftl-login', 'login_obj', array(
            'ajax_url' => admin_url("admin-ajax.php"),
            'home_url' => home_url('/'),
        ));
    }
}

//This function create form new login
if(!function_exists('wpftl_form_login')){
    function wpftl_form_login(){
        $current_user = wp_get_current_user();

        $formHTML = '<form method="post" id="login">
                        <div class="form-control">
                            <input type="email" name="email" id="login_email" placeholder="Login" required>    
                        </div>
                        <div class="form-control">
                            <input type="password" name="password" id="login_password" placeholder="Senha" required>        
                        </div>
                        <div class="form-control">
                            <input type="submit" value="Entrar" id="entrar">    
                        </div>        
                    </form>';

        if (is_user_logged_in() === false) {
            $formHTML = '<form method="post" id="login">
                            <div class="form-control">
                                <input type="email" name="email" id="login_email" placeholder="Login" required>    
                            </div>
                            <div class="form-control">
                                <input type="password" name="password" id="login_password" placeholder="Senha" required>        
                            </div>
                            <div class="form-control">
                                <input type="submit" value="Entrar" id="entrar">    
                            </div>        
                        </form>';
            return $formHTML;
        }else{          
            return '<span class="current-user-text">Olá, '. $current_user->user_firstname . '</span>';         
        }
        return $formHTML;
    }
}

//this  function login
if(!function_exists('wpftl_login')){
    function wpftl_login(){

        $array = array('status' => 1);
    
        if(empty($_POST['email']) || empty($_POST['password']) || !is_email($_POST['email'])){
            wp_send_json($array);
        }
    
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
    
        if(!email_exists($email)){
            wp_send_json($array);
        }
    
        $userdata = get_user_by('email', $email);
    
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