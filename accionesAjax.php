
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<span><?php
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }

$id=$_GET['id_provincia'];
echo LocalidadView::getComboLocalidadHTML($id);
?></span>
