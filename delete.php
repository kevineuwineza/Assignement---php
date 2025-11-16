<?php
       $bla=new mysqli("localhost","root","","classb");
       if (isset($_GET['phone_number'])) {
       	$phone_number=$_GET['phone_number'];
       	$delete=$bla->query("DELETE FROM information where phone_number='$phone_number'");
       	if ($delete) {
       		header("location:index.php");
       	}
       }
?>