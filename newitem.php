<?php 

	$name = "";
	$code    = "";
	$image    = "";
	$price    = "";
	$gamintojas     = "";

	$db = mysqli_connect('localhost', 'root', '', 'main');


			$query = "SELECT  kategorija FROM items ";
			$product_array = mysqli_query($db, $query);
	
			$kategorijaselect = array();


			while ($result = $product_array->fetch_assoc()){

				array_push($kategorijaselect, $result['kategorija']);}


			$kategorijaselect = array_filter(array_unique($kategorijaselect));


	if (isset($_POST['new_item'])) {
	
		$kategorija = mysqli_real_escape_string($db, $_POST['kategorija']);
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$code = mysqli_real_escape_string($db, $_POST['code']);
		$gamintojas = mysqli_real_escape_string($db, $_POST['gamintojas']);
		$image = mysqli_real_escape_string($db, $_FILES["image"]["name"]);
		$price = mysqli_real_escape_string($db, $_POST['price']);
		if ($_POST['sandeli']=="Yra")
			$sandeli = '1';
		else
			$sandeli = '0';

		$target_dir = "product-images/";
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		  $check = getimagesize($_FILES["image"]["tmp_name"]);
		  if($check !== false) {
			$uploadOk = 1;
		  } else {
			$uploadOk = 0;
			}

		move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);


			$query = "INSERT INTO items (kategorija, name, code, gamintojas, image, price, sandeli) 
					  VALUES('$kategorija','$name', '$code', '$gamintojas', '$image', '$price','$sandeli')";
			mysqli_query($db, $query);

			header('location: newitem.php');

		}



?>

<!DOCTYPE html>
<html>
<head>
	<title>New item</title>
	<link href="style-index.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<div class="input-group">
		<h2>New Item</h2>
	</div>
	
	<form method="post" action="newitem.php" enctype="multipart/form-data">

		<div class="input-group">
			<label class="marginbot block">Category</label>
			<select name="kategorija">  
				  	<?php foreach ($kategorijaselect as $elem) : ?>
					<option value=<?php echo $elem; ?>><?php echo $elem; ?></option>
					<?php endforeach ?>
			</select>
		</div>
		<div class="input-group">
			<label class="marginbot block">Name</label>
			<input type="text" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label class="marginbot block">Code</label>
			<input type="text" name="code" value="<?php echo $code; ?>">
		</div>
		<div class="input-group">
			<label class="marginbot block">Manufacturer</label>
			<input type="text" name="gamintojas" value="<?php echo $gamintojas; ?>">
		</div>
		<div class="input-group">
			<label class="marginbot block">Image</label>
			<input type="file" name="image">
		</div>
		<div class="input-group">
			<label class="marginbot block">Price</label>
			<input type="text" name="price" value="<?php echo $price; ?>">
		</div>
		<div class="input-group">
			<label class="marginbot">In stock</label>  
			<input type="checkbox" name="sandeli" value="Yra">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="new_item">Add Item</button>
			<button type="button" class="btn" onclick="location.href='index.php'">Cancel</button>
		</div>
	</form>
</body>
</html>