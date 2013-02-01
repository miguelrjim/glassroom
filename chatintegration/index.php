<?php
	require_once('Library/FaceBook/FaceBookHandler.php');
	$url = FaceBookHandler::getUserUrl();
	$imageurl = FaceBookHandler::getUserUrlImage();
?>

<!--
  We use the JS SDK to provide a richer user experience. For more info,
  look here: http://github.com/facebook/connect-js
-->
<div>
	<a href="<?php echo $url; ?>">
	  <img src="<?php echo $imageurl; ?>">
	</a>
</div>

<?php if (FaceBookHandler::isLogin()) {	?>
<h3>Friends</h3>
<ol>
<?php 
	$friends = FaceBookHandler::getUserFriendList();

	foreach($friends as $friend) {
		print '<ul>' . $friend['name'] . '---' . $friend['id'] .'</ul>';
	}

?>
</Ol>
<?php } ?>