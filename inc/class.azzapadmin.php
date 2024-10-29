<?php
class AzzapAdmin{
	/**
	 * Plugin admin functions init
	 */
	public static function init(){
		// Menu and options page
		add_action( 'admin_menu', array('AzzapAdmin', 'menu') );
		// Save options action
        // http://wordpress.stackexchange.com/questions/10500/how-do-i-best-handle-custom-plugin-page-actions
		add_action( 'admin_action_azzap_save_options', array('AzzapAdmin', 'actionsaveoptions'));
	}

	/**
	 * Plugin admin menu
	 */
	public static function menu(){
		add_options_page('Azz Anonim Posting Setting', 'Anonim Posting', 'manage_options', 'azzap-options-page', array('AzzapAdmin', 'menuPage'));
	}

	/**
	 * Options page
	 */
	public static function menuPage(){
		AzzapTmpl::render('adminoptions');
	}

	/**
	 * Options form saving
	 */
	public static function actionSaveOptions(){
		foreach(AzzapConfig::$options as $o){
			update_option($o[0],intval($_POST[$o[0]]));
		}
		// One options not INT
		update_option('azzap_content_tmpl',$_POST['azzap_content_tmpl']);

		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}

}