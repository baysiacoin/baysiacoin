<?php
spl_autoload_register(function($name) {
	if (strrpos($name, '\\') !== FALSE) {
		$name = substr($name, strrpos($name, '\\') + 1);
	}
    if(is_file(app_path() . "/Libraries/BaysiaRPCLib/$name.php"))
        require_once(app_path() . "/Libraries/BaysiaRPCLib/$name.php");
});