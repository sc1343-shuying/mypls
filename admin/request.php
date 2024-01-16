<?php
    session_start();
	require_once "MyUtils.class.php";
	$my = new MyUtils();
	$title = "disscussion page";
	$style = "home_page.css";
	$page = "Add Request";
    $userid = "";
    $eventId = "";
    if(isset( $_SESSION['role'])){
    	 $userid =  $_SESSION['role'];
    }
    $_SESSION['admin'] =  $userid;
	echo $my->admin_header($title,$style,$page,$userid);
?>

        
<?php	
	//check if user login to admin
	if (!isset($_SESSION['event']) && !isset($_GET['id'])) {
        echo "<script>document.location.href='login.php?success=1';</script>";
			exit();
	} 

?>	

<?php
	require_once "DB.class.php";
		$db = new DB();
		
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}
	$db->sendRequest($id ,$userid);

?>

<?php
	echo $my->html_footer();
?>


