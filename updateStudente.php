<?php
session_start();
	try{
		
		$con = new PDO("mysql:host=localhost;dbname=studente","root","");
		$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $exc){
		
		echo $exc->getMessage();
		exit();
	}
	
	$id = '';
	$fname = '';
	$age = '';
	
	function getPosts(){
		
		$posts = array();
		$posts[0] = $_POST['st_id'];
		$posts[1] = $_POST['st_naam'];
		$posts[2] = $_POST['st_jaar'];
		
		return $posts;
	}
	
	//insert data
	
	if(isset($_POST['insert'])){
		
		$data = getPosts();
		if(empty($data[1]) || empty($data[2])){
			
			echo 'Enter the user data to  insert';
		}
		else{
			
			$insertStmt = $con->prepare('INSERT INTO stud_details(st_naam,st_jaar) VALUES(:st_naam, :st_jaar)');
			$insertStmt->execute(array(
						':st_naam'=>$data[1],
						':st_jaar'=>$data[2],
			));
			
			if($insertStmt){
			
				echo 'Data inserted</br>';
			}
		}
	}
	
	//Update Data
	
	if(isset($_POST['update'])){
		
		$data = getPosts();
		if(empty($data[0]) || empty($data[1]) || empty($data[2])){
			
			echo 'Enter the user data has been updated';
		}
		else{
			
			$updateStmt = $con->prepare('UPDATE stud_details SET st_naam =:st_naam, st_jaar =:st_jaar WHERE st_id = :st_id');
			$updateStmt->execute(array(
						':st_id'=>$data[0],
						':st_naam'=>$data[1],
						':st_jaar'=>$data[2],
			));
			
			if($updateStmt){
			
				echo 'Data updated';
			}
		}
	}
	
	//Delete Data
	
	if(isset($_POST['delete'])){
		
		$data = getPosts();
		if(empty($data[0])){
			
			echo 'Enter the user data to be deleted';
		}
		else{
			
			$deleteStmt = $con->prepare('DELETE FROM stud_details WHERE st_id = :st_id');
			$deleteStmt->execute(array(
						':st_id'=>$data[0],
			
			));
			
			if($deleteStmt){
			
				echo 'Data delete successfully';
			}
		}
	}
?>	

<table class="table table-hover table-dark table-reponsive-sm">
<thead><td>ID</td><td>NAME</td><td>AGE</td></thead>
	
<?php
	//display table data

if(isset($_POST['display'])){

try{
	$con = new PDO("mysql:host=localhost;dbname=studente","root","");
	$select = $con->prepare("SELECT * FROM stud_details");
	$select->setFetchMode(PDO::FETCH_ASSOC);
	$select->execute();
	
	while($data= $select->fetch()){ ?>

<tr>

<td><?php echo $data['st_id']; ?> </td>
<td><?php echo $data['st_naam']; ?> </td>
<td><?php echo $data['st_jaar'];?> </td></tr>

<?php
}
}
catch(PDOException $e){
	
echo "$e".getMessage();	
}
}
?>

<?php

	try{
		
		$con = new PDO("mysql:host=localhost;dbname=studente","root","");
		$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $exc){
		
		echo $exc->getMessage();
		exit();
	}
	
	$id = '';
	$fname = '';
	$age = '';
	
	/*function getPosts(){
		
		$posts = array();
		$posts[0] = $_POST['st_id'];
		$posts[1] = $_POST['st_naam'];
		$posts[2] = $_POST['st_jaar'];
		
		return $posts;
	}*/
	
	//Search and Display Data 
	if(isset ($_POST['search'])){
		
		$data = getPosts();
		if(empty($data[0])){
			
			echo 'Enter the  user ID to search';
		}
		else{
			
			$searchStmt = $con->prepare('SELECT * FROM stud_details WHERE st_id = :st_id');
			$searchStmt->execute(array(
						':st_id'=>$data[0]
			));
			
			if($searchStmt){
			$user = $searchStmt->fetch();
			
			$id = $user[0];
			$fname = $user[1];
			$age = $user[2];
			
			echo 'Here are your results';
			}
		}
	}
	?>
<!DOCTYPE html>

<html> 
<head><title>Dit doen alles</title>

<meta charset= "UTF-8">
<meta name ="viewpoint" content="width-device-width, inital-scale-1.0">
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<body>
<div style= "background-color: lightblue">
<div class="jumbotron text-center" style= "background-color: blue">
<h1>Insert/ Delete/ Update/ Display Data</h1></div>
<hr>
<div class="container">
<div style= "background-color:">
<form autocomplete="off" action ="updateStudente.php"  method="post">
ID <input type ="number" class="form-control" name="st_id" placeholder="ID" value="<?php echo $id;?>">

<div class="valid-feedback">
        Looks good!
		<span class="glyphicon">&#xe013;</span>
      </div>
	NAME<input type ="text" class="form-control is-valid" id="validationServer01" name="st_naam" placeholder="Naam" value="<?php echo $fname;?>">
	AGE <input type ="text" class="form-control" name="st_jaar" placeholder="Age" value="<?php echo $age;?>">

<button type ="submit" class="btn btn-primary" name="insert" value="insert data">Insert</button>
<button type ="submit" class="btn btn-primary" name="update" value="udpate data">Update</button>
<button type ="submit" class="btn btn-primary" name="delete" value="delete data">Delete</button>
<button type ="submit" class="btn btn-primary" name="search" value="search data">Search</button>
<button type ="submit" class="btn btn-primary" name="display" value="display data">Display</button></br></br>

</form>

</table>
</div>
</div>
</div>
</body>
</html>
