<?php  if (count($errors) > 0) : ?>
	<div style="padding-left: 10px;">
		<?php foreach ($errors as $error) : ?>
			<p style="color: red; style: block;"><?php echo $error ?></p>
		<?php endforeach ?>
	</div>
<?php  endif ?>
