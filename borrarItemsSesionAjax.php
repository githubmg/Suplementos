
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<span>
<?php
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }
		session_start();
		if (isset($_SESSION['items'])){
			$_SESSION['items'] = null;
		}
		
?>
</span>
