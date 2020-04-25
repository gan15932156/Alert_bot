<?php
	// $break = FALSE;
	// while($break == FALSE)
	// 	{
	// 		if($now != $base)
	// 		{
	// 			sleep(60);
	// 		}
	// 		elseif($now == $base)
	// 		{
	// 			mail($to, $subject, $message, $headers);
	// 			sleep(60); //In order to make the next execution == FALSE
	// 		}
	// 	}
	$break = FALSE;
	$i = 1;
	while($break == FALSE){
		if($i == 5){
			sleep(1);
			$break = true;
		}	
		else{
			sleep(1);
			echo $i."<br>";
		}
		$i++;
	}
?>