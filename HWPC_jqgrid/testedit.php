<?php 
//$olderrorlevel = error_reporting(0); //turn off php spews for now

//include the information needed for the connection to MySQL data base server. 
// we store here username, database and password 
//include("dbconfig.php");
$dbhost = 'localhost';
$dbuser   = 'root';
$dbpassword = '';
$database = 'griddemo';
// Since we specify in the options of the grid that we will use a GET method 
// we should use the appropriate command to obtain the parameters. 
// In our case this is $_GET. If we specify that we want to use post 
// we should use $_POST. Maybe the better way is to use $_REQUEST, which
// contain both the GET and POST variables. For more information refer to php documentation.
// Get the requested page. By default grid sets this to 1. 

// $_POST is what the jqgrid uses on the EDIT URL
//			{name:'id', index:'id', width:55}, 
//			{name:'invdate', index:'invdate', width:90}, 
//			{name:'amount', index:'amount', width:80, align:'right', editable:true}, 
//			{name:'tax', index:'tax', width:80, align:'right', editable:true}, 
//			{name:'total', index:'total', width:80, align:'right', editable:true}, 
//			{name:'note', index:'note', width:150, sortable:false, editable:true} 
$amount = $_POST['amount'];
$tax = $_POST['tax'];
$total = $_POST['total'];
$note = $_POST['note'];			
$id = $_POST['id'];
 
// connect to the ODBC database server 
$cid = odbc_connect($database, $dbuser, $dbpassword) or die("Hello Chris I got Connection Error: " . odbc_error()); 
 
// calculate the number of rows for the query. We need this for paging the result 
$sql = "UPDATE invheader SET amount='".$amount."', tax='".$tax."', total='".$total."', note='".$note."' WHERE id=".$id; 
$result = odbc_exec($cid,$sql);

//error_reporting($olderrorlevel); //put PHP back to old error spew now
?>