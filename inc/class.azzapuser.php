<?php
class AzzapUser{
	private static $post_options = array();
    private static $attaches = array();

    public static function init(){
		self::$post_options['author'] = intval( get_option('azzap_user_id') );
		self::$post_options['status'] = intval( get_option('azzap_publish_imm')) == 1 ? 'publish' : 'pending';
    }


    public static function actionUploadImage(){
	    $args = array(
		    'script_url' => AZZAP_PLUGIN_URL,
		    'upload_dir' => AZZAP_FILES_DIR,
		    'upload_url' => AZZAP_FILES_URL,
		    'max_file_size' => intval(get_option('azzap_max_file_size'))*1000000,
	    );
        $image = new AzzapUploadHandler($args, false);
        $image->post();
    }

	/**
	 * Render add post form
	 */
	public static function pageAddPostForm(){
        wp_enqueue_script('azzap_script' , AZZAP_PLUGIN_URL.'js/fu/jquery.ui.widget.js', array('jquery') );
        wp_enqueue_script('azzap_script1', AZZAP_PLUGIN_URL.'js/fu/jquery.iframe-transport.js', array('jquery') );
        wp_enqueue_script('azzap_script2', AZZAP_PLUGIN_URL.'js/fu/jquery.fileupload.js', array('jquery') );
        wp_enqueue_script('azzap_script3', AZZAP_PLUGIN_URL.'js/script.js', array('jquery') );

        wp_enqueue_style('azzap_style', AZZAP_PLUGIN_URL.'style.css');

        if(!AzzapTmpl::render('form')) echo '<!-- Azzap: no such form -->';
    }

    /**
     * Save add post form data
     */
    public static function actionSaveForm(){
	    self::init();

		try{
			$post_id = self::addPost();
			$azzap_cookie = array('error'=>false,'message'=> 'Your post added successfully and awaiting moderation.');

			// Save images
	        if(isset($_POST['uploaded']) && intval(get_option('azzap_attach')) == 1){
		        // Needed libraries
	            require_once( ABSPATH . 'wp-admin/includes/image.php' );
	            require_once( ABSPATH . 'wp-admin/includes/file.php' );
	            require_once( ABSPATH . 'wp-admin/includes/media.php' );

		        // For each uploade image - move them to WP media library
	            foreach($_POST['uploaded'] as $fuImage){
	                self::$attaches[] = self::moveImage($fuImage, $post_id);
	            }

		        // Update post with content added attaches
				$content = self::renderContent(wp_strip_all_tags( $_POST['azzap-post-content']));
		        $my_post = array('ID'=>$post_id, 'post_content'=>$content);

		        wp_update_post($my_post);
	        }
		}catch (Exception $e){
			$azzap_cookie = array('error'=>true,'message'=>$e->getMessage());
		}

	    setcookie('azzap_messaging', json_encode($azzap_cookie));

	    if(!$azzap_cookie['error'] && self::$post_options['status'] == 'publish'){
		    $redirect_url = get_permalink($post_id);
	    }else{
		    $redirect_url = @$_SERVER['HTTP_REFERER'] ;
	    }

        wp_redirect( $redirect_url );
        exit();
    }

    /**
     * Move image from temporary jQuery File Upload directory to wp upload dir
     * Why not use files stored in azzap_files dir
     * - media_handle_sideload generate attachment in media library
     * - generate images sized with your wp options, included thumbnails
     * - apply all filters and actions to image ( like Watermarks )
     */
    private static function moveImage($fuName, $post_id = 0){
        $file   = AZZAP_FILES_DIR.$fuName;

        $wp_filetype = wp_check_filetype( basename( $file ), null );
        $aFile["name"]      = basename( $file );
        $aFile["type"]      = $wp_filetype;
        $aFile["tmp_name"]  = $file;
        $attach_id          =  media_handle_sideload( $aFile, $post_id );

	    // Delete temporary files
        if( is_file($file) )    unlink($file);

        $thumb  =  AZZAP_FILES_DIR.'thumbnail/'.$fuName;
        if( is_file($thumb) )   unlink($thumb);

        return $attach_id;
    }

	/**
	 * Generate post from $_POST data
	 * return post ID
	 */
	private static function addPost(){

		if(!isset($_POST['azzap-post-title']) || strlen($_POST['azzap-post-title']) < 1)
			throw new Exception('Empty title');

		if(!isset($_POST['azzap-post-content']) || strlen($_POST['azzap-post-content']) < 1)
			throw new Exception('Empty post content');

		$my_post = array(
			'post_title'    => wp_strip_all_tags( $_POST['azzap-post-title']),
			'post_content'  => wp_strip_all_tags( $_POST['azzap-post-content']),
			'post_status'   => self::$post_options['status'],
			'post_author'   => self::$post_options['author'],
			'comment_status'=>'open'
		);

		if(isset($_POST['azzap-category']) && intval($_POST['azzap-category']) > 0)
			$my_post['post_category'] = array(intval($_POST['azzap-category']));

		// Insert the post into the database
		if(!$pid = wp_insert_post( $my_post ))
			throw new Exception('Save post error');

		if(isset($_POST['azzap-user-name']))
			add_post_meta($pid, 'azzap-user-name', wp_strip_all_tags( $_POST['azzap-user-name']));

		if(isset($_POST['azzap-user-email']))
			add_post_meta($pid, 'azzap-user-email', wp_strip_all_tags( $_POST['azzap-user-email']));

		return $pid;
	}

	/**
	 * Render post from content and attaches
	 */
	private static function renderContent($content){
        /**
         * На каждый img массив прикрепленных аттачей уменьшается
         * в конце в images:thumbnail прикрепляются остатки
         * если что-то осталось
         * $content_tmpl = '[img:thumbnail]<br/>[img:large]<br/>[img:medium]<br/>[content]<br/>[images:thumbnail]';
         */

        $content_tmpl = get_option('azzap_content_tmpl');

        $content_tmpl = preg_replace_callback('/\[img:(\w+)\]/i', array('AzzapUser','callbackReplaceImg'), $content_tmpl);
        $content_tmpl = preg_replace_callback('/\[images:(\w+)\]/i', array('AzzapUser','callbackReplaceImages'), $content_tmpl);
        $content_tmpl = preg_replace('/\[content]/i', $content, $content_tmpl);

        return $content_tmpl;
	}

    // Replace single image
    // по порядку из массива
    // массив уменьшается
    private static function callbackReplaceImg($size){
        $attach_id = array_shift(self::$attaches);
        if($attach_id === NULL) return '';
        return wp_get_attachment_link( intval($attach_id), $size[1] );
    }

    // Render last images
    private static function callbackReplaceImages($size){
        if(count(self::$attaches) < 1 ) return '';

        $out = '';
        foreach(self::$attaches as $a){
            $out .= wp_get_attachment_link( intval($a), $size[1] );
        }

        return '<p class="azzap_images_arr">'.$out.'</p>';
    }
}