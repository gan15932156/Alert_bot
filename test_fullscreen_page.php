<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<style type="text/css">

body {
	margin: 0;
}

 

#go-button {
	width: 200px;
	display: block;
	margin: 50px auto 0 auto;
}

/* webkit requires explicit width, height = 100% of sceeen */
/* webkit also takes margin into account in full screen also - so margin should be removed (otherwise black areas will be seen) */
#element:-webkit-full-screen {
	width: 100%;
	height: 100%;
	background-color: pink;
	margin: 0;
}

#element:-moz-full-screen {
	background-color: pink;
	margin: 0;
}

#element:-ms-fullscreen {
	background-color: pink;
	margin: 0;
}

/* W3C proposal that will eventually come in all browsers */
#element:fullscreen { 
	background-color: red;
	margin: 0;
}
#tb{
	overflow-x: scroll;
   overflow-y: scroll;
}
.tb_fullcreen:fullscreen{
 
	overflow-x: scroll;
   overflow-y: scroll;
   background-color: blue;
	margin: 0;
   padding: 0;
}
.tb_fullcreen{
	width:20vw;
	height:40vh;
}
#go-button:fullscreen{
   width:100vw;
   height:100vh;
   background-color: green;
	margin: 0;
   padding: 0;
}
</style>

</head>

<body>
	<div id="element">
		<span>Full Screen Mode Disabled</span>
		<button id="go-button">Enable Full Screen</button>
		<div class="tb_fullcreen">
			<table id="tb">
				<thead>
					<tr>
						<th>dasd</th>
						<th>asda</th>
						<th>dasda</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr><tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr> <tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>2</td>
					</tr>

				</tbody>
			</table>
		</div>
     
	</div>
</body>

<script>

/* Get into full screen */
function GoInFullscreen(element) {
	if(element.requestFullscreen)
		element.requestFullscreen();
	else if(element.mozRequestFullScreen)
		element.mozRequestFullScreen();
	else if(element.webkitRequestFullscreen)
		element.webkitRequestFullscreen();
	else if(element.msRequestFullscreen)
		element.msRequestFullscreen();
}

/* Get out of full screen */
function GoOutFullscreen() {
	if(document.exitFullscreen)
		document.exitFullscreen();
	else if(document.mozCancelFullScreen)
		document.mozCancelFullScreen();
	else if(document.webkitExitFullscreen)
		document.webkitExitFullscreen();
	else if(document.msExitFullscreen)
		document.msExitFullscreen();
}

/* Is currently in full screen or not */
function IsFullScreenCurrently() {
	var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
	
	// If no element is in full-screen
	if(full_screen_element === null)
		return false;
	else
		return true;
}

$("#go-button").on('click', function() {
	if(IsFullScreenCurrently())
		GoOutFullscreen();
	else
		GoInFullscreen($(".tb_fullcreen").get(0));
});

$(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
	if(IsFullScreenCurrently()) {
		$("#element span").text('Full Screen Mode Enabled');
		$("#go-button").text('Disable Full Screen');
	}
	else {
		$("#element span").text('Full Screen Mode Disabled');
		$("#go-button").text('Enable Full Screen');
	}
});

</script>

</html>