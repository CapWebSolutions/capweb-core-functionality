function capweb_login_logo() { ?>
    <style type="text/css">
		

		/* CHANGE THESE COLORS TO MATCH YOUR BRAND */ 
		:root {
			--brand-color: #64748B;
			--brand-color-hover: #334155;
			--background-color: #f8fafc;
		}
		/* Body */ 
		body.login{
			background: var(--background-color);
			display: flex;
		}		

		/* Logo */ 
        #login h1 a, .login h1 a {
			background-image: url(https://capwebsolutions.com/wp-content/uploads/2016/03/TRANS-CapWebSolutions_Logo_NoCap-504x152.png); 
			height:80px; /* Adjust height if needed */ 
			width: 100%;
			max-width:263px; /* Adjust height if needed */ 
			background-size: contain;
			background-repeat: no-repeat;
		}

		/* Login wrapper */ 
		body.login div#login {
			padding: clamp(2rem, 0.714rem + 1.429vw, 3rem);
			margin-block: auto;
			margin-inline: auto;
			border-radius: 1.5em;
			box-shadow:
				0px 2.8px 2.2px rgba(0, 0, 0, 0.006),
				0px 6.7px 5.3px rgba(0, 0, 0, 0.008),
				0px 12.5px 10px rgba(0, 0, 0, 0.01),
				0px 22.3px 17.9px rgba(0, 0, 0, 0.012),
				0px 41.8px 33.4px rgba(0, 0, 0, 0.014),
				0px 100px 80px rgba(0, 0, 0, 0.02);
			background-color: white;
		}

		/* Login form */ 
		body.login div#login form {
			border: none;
			box-shadow: none;
			padding: 1rem 1rem 2rem;
		}

	
		/* Form inputs focus color */ 
		body.login input:focus{
			outline: 2px solid var(--brand-color);
			border: 0px ;
		}

		/* Submit button */ 
		body.login div#login #wp-submit {
			background-color: var(--brand-color); 
			border: 0px;
		}

		/* Submit button on hover */ 
		body.login div#login #wp-submit:hover {
			background-color: var(--brand-color-hover);
		}

		
		/* "Lost your passworld" link hover color */ 
		body.login div#login p#nav a:hover, body.login div#login p#backtoblog a:hover{
			color: var(--brand-color-hover);
		}

		/* "Lost your password" positioning */ 		
		body.login div#login p#nav, body.login div#login p#backtoblog{
			display: flex;
			justify-content: center;
			margin-top: .5rem;
		}
		
		body.login .message{
			border-left: 4px solid var(--brand-color-hover);
		}
		
			.custom-support-link {
	position: fixed; 
	bottom: 12px; 
	left: 50%; 
	transform: translatex(-50%);
	color: var(--surface-70);
				text-decoration: none;
	}
		
		.custom-support-link:hover{
			color: var(--surface-90);
		}
		
		body.login div#backtoblog {
    display: none;
}


		.login #backtoblog a {
    display: none;
}
		

    </style>

<?php }
add_action( 'login_enqueue_scripts', 'capweb_login_logo' );
function capweb_login_logo_url() {
    return 'https://capwebsolutions.com';  
}
add_filter( 'login_headerurl', 'capweb_login_logo_url' );
// Redirect users to /lists after login
function custom_login_redirect($redirect_to, $request, $user) {
    // If login is successful, redirect to the /lists page
    if (isset($user->roles) && is_array($user->roles)) {
        return home_url('/lists');  // Redirect to /lists
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// Remove the admin bar for all users except administrators
function hide_admin_bar_from_non_admins() {
    if (current_user_can('administrator')) {
        return true;
    }
    return false;
}
add_filter('show_admin_bar', 'hide_admin_bar_from_non_admins');


// Redirect non-admin users if they try to access the backend
function redirect_non_admin_users() {
    if (is_admin() && !defined('DOING_AJAX') && !current_user_can('administrator')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('init', 'redirect_non_admin_users');
