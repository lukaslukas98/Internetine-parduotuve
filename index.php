<?php
	session_start();
	//require_once("dbcontroller.php");
	//$db_handle = new DBController();
	
	$db = mysqli_connect('localhost', 'root', '', 'main');

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: index.php");
	}


	if(!isset($_SESSION['admin'])){$_SESSION['admin']=0;}


if(!empty($_GET["action"])) {
switch($_GET["action"]) {

//add check for negative and decimal quantity!!!!!!!!!!!!!!!
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM items WHERE code='" . $_GET["code"] . "'"));	
			$itemArray = array($productByCode["code"]=>array('name'=>$productByCode["name"],'kategorija'=>$productByCode["kategorija"], 'code'=>$productByCode["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode["price"], 'image'=>$productByCode["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode["code"] == $k) {
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} 
			else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;

	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;

	case "empty":
		unset($_SESSION["cart_item"]);
	break;	

	case "delete":
		mysqli_query($db,"DELETE FROM items WHERE code='" . $_GET["code"] . "'");
	break;	
	}
}
?>
<HTML>
<HEAD>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<TITLE>Index</TITLE>
<link href="style-index.css" type="text/css" rel="stylesheet" />

<div id="header">
	<?php
		if (isset($_SESSION['username'])) {
			echo "Hi, ". $_SESSION['username'] ."! ". '<a id="btnLogout" href="index.php?logout="1"">Logout?</a>';
		}
		else {	
		include('login.php') ;
		include('errors.php');
	?>
	<form method="post" >
		<div>
			<div class="divblock">
			<label class="logintext">Username</label>
			<input type="text" name="username" class="inputfield marginbot" >
			</div>
			<div class="divblock">
			<label class="logintext">Password</label>
			<input type="password" name="password" class="inputfield marginbot">
			<button type="submit" class="btn" name="login_user" class="inputfield marginbot">Login</button>
			</div>
			<div class="divblock">
			Not yet a member? <a onclick="openForm()" id="signupbutton">Sign up</a>
			</div>
		</div>

	</form>
	<?php
	}
	?>
</div>


<script>
function openForm() { document.getElementById("myForm").style.display = "block"; }
function closeForm() { document.getElementById("myForm").style.display = "none"; }
</script>

<div class="form-popup" id="myForm">
 <form method="post">

		<?php 
		include('register.php'); 
		include('errors.php');
		if (count($errors) != 0) : ?>
		<script>openForm();</script>
		<?php  endif ?>
		<div class="header input-group">
		<h2>Registration</h2>
		</div>
		<div class="input-group">
			<label class="regtext">Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<label class="regtext">Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<label class="regtext">Password</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label class="regtext">Confirm password</label>
			<input type="password" name="password_2">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="reg_user">Register</button>
			<button type="button" class="btn" onclick= closeForm()>Cancel</button>
		</div>
	</form>
</div>


</HEAD>
<BODY>
<div id="shopping-cart">
	<div class="txt-heading">Shopping Cart</div>
	<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>

	<?php
		if(isset($_SESSION["cart_item"])){
			$total_quantity = 0;
			$total_price = 0;
		?>	
			<table class="tbl-cart" cellpadding="10" cellspacing="1">
				<tbody>
					<tr>
						<th style="text-align:left;">Name</th>
						<th style="text-align:left;">Category</th>
						<th style="text-align:left;">Code</th>
						<th style="text-align:right;" width="5%">Quantity</th>
						<th style="text-align:right;" width="10%">Unit Price</th>
						<th style="text-align:right;" width="10%">Price</th>
						<th style="text-align:center;" width="5%">Remove</th>
					</tr>	

					<?php		
						foreach ($_SESSION["cart_item"] as $item){
							$item_price = $item["quantity"]*$item["price"];
							?>
								<tr>
									<td><img src="product-images/<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
									<td><?php echo $item["kategorija"]; ?></td>
									<td><?php echo $item["code"]; ?></td>
									<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
									<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
									<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
									<td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
								</tr>
							<?php
							$total_quantity += $item["quantity"];
							$total_price += $item_price;
						}
					?>

					<tr>
						<td colspan="3" align="right">Total:</td>
						<td align="right"><?php echo $total_quantity; ?></td>
						<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
						<td></td>
					</tr>
				</tbody>
			</table>		
		<?php
		} else {
		?>
		<div class="no-records">Your Cart is Empty</div>
		<?php 
		}
	?>
</div>




<?php 	
			$query = "SELECT kategorija, name, gamintojas FROM items ";
			$product_array = mysqli_query($db, $query);
	
			$kategorija = array();
			$name = array();
			$gamintojas = array();

			while ($result = $product_array->fetch_assoc()){
				array_push($kategorija, $result['kategorija']);
				array_push($name, $result['name']);
				array_push($gamintojas, $result['gamintojas']);
			}

			$kategorija = array_filter(array_unique($kategorija));
			$name = array_filter(array_unique($name));
			$gamintojas = array_filter(array_unique($gamintojas));
?>

<div id="product-grid">
	<div class="txt-heading">Products</div>
	<form method="post" action="index.php">
		<div class="input-group">
			<div class="divblock">
			<label>Category:</label>
			<select name="kategorija" class="inputfield marginbot">  
				<option value="Select">Select</option>  
				<?php foreach ($kategorija as $elem) : ?>
				<option <?php if(!empty($_POST['kategorija'])){if($elem == $_POST['kategorija']) echo 'selected="selected"'; }?> value=<?php echo $elem; ?>><?php echo $elem; ?></option>
				<?php endforeach ?>
			</select>
			</div>
			<div class="divblock">
			<label>Model:</label>
			<select name="modelis" class="inputfield marginbot">  
				<option value="Select">Select</option>  				  		
				<?php foreach ($name as $elem) : ?>
				<option <?php if(!empty($_POST['modelis'])){if($elem == $_POST['modelis']) echo 'selected="selected"'; }?> value=<?php echo "'".$elem."'"; ?>><?php echo $elem; ?></option>
				<?php endforeach ?> 
			</select>
			</div>
			<div class="divblock">
			<label>Manufacturer:</label>
			<select name="gamintojas" class="inputfield marginbot">  
				<option value="Select">Select</option>  
				<?php foreach ($gamintojas as $elem) : ?>
				<option <?php if(!empty($_POST['gamintojas'])){if($elem == $_POST['gamintojas']) echo 'selected="selected"'; }?> value=<?php echo $elem; ?>><?php echo $elem; ?></option>
				<?php endforeach ?> 
			</select>
			</div>
			<div class="divblock">
			<label>In stock:</label>
			<select name="sandelis" class="inputfield marginbot">  
				<option value="Select">Select</option>  
				<option <?php if(!empty($_POST['sandelis'])){if($_POST['sandelis'] == 'Yes') echo 'selected="selected"'; }?> value="Yes">Yes</option>
				<option <?php if(!empty($_POST['sandelis'])){if($_POST['sandelis'] == 'No') echo 'selected="selected"'; }?>  value="No">No</option>
			</select>
			</div>
			<button type="submit" class="btn" name="filter">Filter</button>
		</div>
	</form>


	<?php
	if ($_SESSION['admin']!=0) 
		echo '<a id="btnNewItem" href="newitem.php">New Item</a><br>';
		
	if (!empty($_POST['kategorija'])) {
		if($_POST['kategorija'] == 'Select'){
		$kategorija = "WHERE (kategorija is not null or kategorija is null)";
		}
		else{
		$kategorija ="WHERE kategorija = '". $_POST['kategorija']."'";
		}
	
		if($_POST['modelis'] == 'Select'){
		$modelis = "(name is not null or name is null)";
		}
		else{
		$modelis ="name = '". $_POST['modelis']."'";
		}
	
		if($_POST['gamintojas'] == 'Select'){
		$gamintojas = "(gamintojas is not null or gamintojas is null)";
		}
		else{
		$gamintojas ="gamintojas = '". $_POST['gamintojas']."'";
		}

		switch ($_POST['sandelis']) {
			case 'Select':
				$sandelis = "(sandeli is not null or sandeli is null)";
				break;
			case 'Yes':
				$sandelis = 'sandeli = 1';
				break;
			case 'No':
				$sandelis = 'sandeli = 0';
				break;
			default:
				break;
		}
			
		$query = "SELECT * FROM items $kategorija and $modelis and $gamintojas and $sandelis ORDER BY id ASC";
	}
	else{
		$query = "SELECT * FROM items ORDER BY id ASC";
	}

	$result = mysqli_query($db,$query);

	while($row=mysqli_fetch_assoc($result)) {
		$resultset[] = $row;
	}		
	if(!empty($resultset))
	$product_array = $resultset;

	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
		<div class="product-item">
			<form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
				<div class="product-image">
				<?php if(!empty($product_array[$key]["image"])){?>
				<img class="product-image" src="product-images/<?php echo $product_array[$key]["image"]; ?>">
				<?php } ?>
				</div>
				<div class="product-tile-footer">
					<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
					<div class="product-title">In stock: 
					<?php 
					if ($product_array[$key]["sandeli"] == 1) echo "Yes";
					else echo '<span style="color: red;">No</span>';
					?>
					</div>
					<div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>
					<div class="cart-action">
						<input type="text" class="product-quantity" name="quantity" value="1" size="2" />
						<input type="submit" value="Add to Cart" class="btnAddAction" />
					</div>
				</div>
			</form>

			<?php if ($_SESSION['admin']!=0) 	{	?>
			<div class="input-group">
			<input type="submit" value="Delete Item" class="btnDeleteItem" onclick='location.href="index.php?action=delete&code=<?php echo $product_array[$key]["code"]; ?>"' />
			<input type="submit" value="Edit Item" class="btnEditItem" onclick='location.href="edititem.php?code=<?php echo $product_array[$key]["code"]; ?>"' />
			</div>
			<?php  } ?>
		</div>
	<?php
		}
	}
	?>
</div>
</BODY>
</HTML>