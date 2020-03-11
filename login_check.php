<?php

   if(empty($_SESSION['username'])){
      echo '<script type="text/javascript">window.location.href="index.php"</script>';
   }
?>