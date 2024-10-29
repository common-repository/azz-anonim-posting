<?php
class AzzapConfig{
	// Default options array
    public static $options = array(
        array('azzap_user_id','2'),
        array('azzap_publish_imm','1'),
        array('azzap_attach','1'),
        array('azzap_max_file_size','1'), //Mb
        array('azzap_user_data','1'),
        array('azzap_content_tmpl','[img:thumbnail]<br/>[content]<br/>[images:thumbnail]')
    );

	// Main Ini function
    public static function init(){
        add_filter('query_vars', array('AzzapConfig','query_vars'));
        add_action('parse_request', array('AzzapConfig','request'));
    }

    /**
     * Register query vars
     */
    public static function query_vars($vars) {
        $vars[] = AZZAP_QUERY_VAR;
        return $vars;
    }

    /**
     * Query Urls
     */
	public static function request($wp){
        if (array_key_exists(AZZAP_QUERY_VAR, $wp->query_vars)) {
            $func = 'action'.htmlspecialchars($wp->query_vars['azzap_query']);
            if(method_exists('AzzapUser', $func))
                AzzapUser::$func();
            exit();
        }
    }

    public static function install(){
        foreach(self::$options as $o){
            update_option($o[0],$o[1]);
        }
    }

    public static function deinstall(){
        foreach(self::$options as $o)
            delete_option($o[0]);
    }
}
