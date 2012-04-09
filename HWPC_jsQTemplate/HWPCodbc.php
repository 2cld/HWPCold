<?php 
$olderrorlevel = error_reporting(0); //turn off php spews for now

//include the information needed for the connection to MySQL data base server. 
// we store here username, database and password 
//include("dbconfig.php");
$dbhost = 'localhost';
$dbuser   = 'root';
$dbpassword = '';
$database = 'HWPC';
// to the url parameter are added 4 parameters as described in colModel
// we should get these parameters to construct the needed query
// Since we specify in the options of the grid that we will use a GET method 
// we should use the appropriate command to obtain the parameters. 
// In our case this is $_GET. If we specify that we want to use post 
// we should use $_POST. Maybe the better way is to use $_REQUEST, which
// contain both the GET and POST variables. For more information refer to php documentation.
// Get the requested page. By default grid sets this to 1. 
$page = $_GET['page']; 
 
// get how many rows we want to have into the grid - rowNum parameter in the grid 
$limit = $_GET['rows']; 
 
// get index row - i.e. user click to sort. At first time sortname parameter -
// after that the index from colModel 
$sidx = $_GET['sidx']; 
 
// sorting order - at first time sortorder 
$sord = $_GET['sord']; 
 
// if we not pass at first time index use the first column for the index or what you want
if(!$sidx) $sidx =1; 
 
// connect to the ODBC database server 
$cid = odbc_connect($database, $dbuser, $dbpassword) or die("Hello Chris I got Connection Error: " . odbc_error()); 
 
// calculate the number of rows for the query. We need this for paging the result 
$sql = ("SELECT COUNT(*) AS count FROM Tickets"); 
$result = odbc_exec($cid,$sql);
//--CAT edit mysql to odbc need to look up mysql_fetch_array vs odbc_fetch_object($result)
$row = odbc_fetch_array($result); 
$count = $row['count']; 
 
// calculate the total pages for the query 
if( $count > 0 && $limit > 0) { 
              $total_pages = ceil($count/$limit); 
} else { 
              $total_pages = 0; 
} 
 
// if for some reasons the requested page is greater than the total 
// set the requested page to total page 
if ($page > $total_pages) $page=$total_pages;
 
// calculate the starting position of the rows 
$start = $limit*$page - $limit;
 
// if for some reasons start position is negative set it to 0 
// typical case is that the user type 0 for the requested page 
if($start <0) $start = 0; 
 
// the actual query for the grid data 
$SQL = "SELECT * FROM Tickets"; 
$result = odbc_exec( $cid, $SQL ) or die("Hello Chris I couldn't execute query: ".odbc_error()); 
 
// we should set the appropriate header information. Do not forget this.
header("Content-type: text/xml;charset=utf-8");
 
$et = ">";
$s = "<?xml version='1.0' encoding='utf-8'?$et\n";
$s .=  "<rows>";
$s .= "<page>".$page."</page>";
$s .= "<total>".$total_pages."</total>";
$s .= "<records>".$count."</records>";
 
// be sure to put text data in CDATA
while($row = odbc_fetch_array($result)) {
    $s .= "<row id='". $row[Tickets]."'>";            
    $s .= "<cell>". $row[TicketID]."</cell>";
    $s .= "<cell>". $row[Invoice]."</cell>";
    $s .= "<cell>". $row[Date]."</cell>";
    $s .= "<cell>". $row[PrevBal]."</cell>";
    $s .= "<cell>". $row[Tax]."</cell>";
    $s .= "<cell>". $row[Total]."</cell>";
    $s .= "<cell>". $row[SerFee]."</cell>";
    $s .= "<cell>". $row[Active]."</cell>";
    $s .= "<cell><![CDATA[". $row[note]."]]></cell>";
    $s .= "</row>";
}
$s .= "</rows>"; 
 
echo $s;

error_reporting($olderrorlevel); //put PHP back to old error spew now
?>