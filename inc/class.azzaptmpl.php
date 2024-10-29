<?php
class AzzapTmpl{
	/**
	 * Render template file
	 */
	public static function render($tmplName, $display = true){
		// Check template file exist
		$tmplFile = AZZAP_TMPL_DIR."/$tmplName.php";
		if(!is_file($tmplFile)) return false;

		// Get template
		ob_start();
		include $tmplFile;
		$out = ob_get_contents();
		ob_end_clean();

		// Display or return template
		if($display) echo $out;
		else return $out;
	}

}