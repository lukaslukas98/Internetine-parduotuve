<?php 
	$name = "";
	$code    = "";
	$image    = "";
	$price    = "";
	$gamintojas     = "";

	$db = mysqli_connect('localhost', 'root', '', 'main');

	$query = ("SELECT * FROM items WHERE code='" . $_GET["code"] . "'");
	$productByCode=mysqli_query($db, $query);
	$result=$productByCode->fetch_array(MYSQLI_ASSOC);

	$kategorija = $result["kategorija"];
	$name = $result["name"];
	$code    = $result["code"];
	$gamintojas    = $result["gamintojas"];
	$price    = $result["price"];
	$sandeli    = $result["sandeli"];

	$query = "SELECT kategorija FROM items ";
	$product_array = mysqli_query($db, $query);
	
	$kategorijaselect = array();

	while ($result = $product_array->fetch_assoc()){
		array_push($kategorijaselect, $result['kategorija']);}

	$kategorijaselect = array_filter(array_unique($kategorijaselect));


	if (isset($_POST['edit_item'])) {

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
		  } 
		  else {
			$uploadOk = 0;
			}
		move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);



		$query = "UPDATE items SET kategorija = '$kategorija', name = '$name', code = '$code', gamintojas = '$gamintojas', image = '$image', price = '$price', sandeli = '$sandeli' WHERE code='" . $_POST['oldcode'] . "'";
		mysqli_query($db, $query);

		header('location: index.php');

	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit item</title>
	<link href="style-index.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<div class="input-group">
		<h2>Edit Item</h2>
	</div>
	
	<form method="post" action="edititem.php" enctype="multipart/form-data">

		<div class="input-group">
			<label class="marginbot block">Category</label>
			<select name="kategorija">  
				  	<?php foreach ($kategorijaselect as $elem) : ?>
					<option <?php if(!empty($kategorija)){if($elem == $kategorija) echo 'selected="selected"'; }?> value=<?php echo $elem; ?>><?php echo $elem; ?></option>
					<?php endforeach ?>
			</select>
		</div>
		<div class="input-group">
			<label class="marginbot block">Name</label>
			<input type="text" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label class="marginbot block">Code</label>
			<input type="text" name="code" value="<?php echo $code;?>">
			<input type="text" name="oldcode" value="<?php echo $code;?>" hidden >
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
			<input type="checkbox" name="sandeli" value="Yra" <?php if($sandeli == 1) echo 'checked'; ?>>
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="edit_item">Confirm</button>
			<button type="button" class="btn" onclick="location.href='index.php'">Cancel</button>
		</div>
	</form>
</body>
</html>