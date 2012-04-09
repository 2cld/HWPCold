<?php 
$olderrorlevel = error_reporting(0); //turn off php spews for now
//From http://www.bin-co.com/php/scripts/sql2json/
//Function will take an SQL query as an argument and format the resulting data as a 
//    json(JavaScript Object Notation) string and return it.
function sql2json($SQLquery) {
	$dbhost = 'localhost';
	$dbuser   = 'christ';
	$dbpassword = 'Fred9Cat9';
	$database = 'HWPC_Test';
	odbc_close_all(); //--Clean up any old stuff
	// connect to the ODBC database server 
	$cid = odbc_connect($database, $dbuser, $dbpassword) or die("Hello Chris I got Connection Error: " . odbc_error()); 
	$data_sql = odbc_exec( $cid, $SQLquery ) or die("Hello Chris I couldn't execute query: ".odbc_error()); 
	$json_str = ""; //Init the JSON string.
	if($total = odbc_num_rows($data_sql)) { //See if there is anything in the query
		$json_str .= "[\n";
		$row_count = 0;    
		while($data = odbc_fetch_array($data_sql)) {
			if($row_count > 0) $json_str .= ","; //If we got a new one and it's not the first add the comma
			if(count($data) > 1) $json_str .= "{\n";
			$count = 0;
			foreach($data as $key => $value) {
				//If it is an associative array we want it in the format of "key":"value"
				$safevalue = urlencode($value);
				if(count($data) > 1) $json_str .= "\"$key\":\"$safevalue\"";
				else $json_str .= "\"$safevalue\"";
				//Make sure that the last item don't have a ',' (comma)
				$count++;
				if($count < count($data)) $json_str .= ",\n";
			}
			$row_count++;
			if(count($data) > 1) $json_str .= "}\n";
		}
		$json_str .= "]\n";
	}
	//Replace the '\n's - make it faster - but at the price of bad redability.
	$json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
	//Finally, output the data
	odbc_close($cid); //--Close this connection.
	return $json_str;
}

// the actual query for the grid data 
//-- Uses HWPC_Test.xls
//--WORKS  $SQL = "SELECT TOP 100000 * FROM [Tickets$]"; 
$RouteID = 11;
/* The data keys for TicketView
	"TicketID": "1001",
	"SerLocID": "1",
	"BillToID": "1",
	"RouteID": "1",
	"SerTypeID": "1",
	"ContName": "Chris Trees",
	"ContPhone": "515-999-0000",
	"BTName": "Chris Trees",
	"BTAddr": "2416 Rownd St.",
	"BTCity": "Cedar Falls",
	"BTState": "IA",
	"BTZip": "50613",
	"SLName": "Alan Trees",
	"SLAddr": "2378 120th St.",
	"SLCity": "Winfield",
	"SLState": "IA",
	"SLZip": "52659",
	"Comment": "Comment text line",
	"TicketDate": "2010-12-15",
	"PrevBal": "0.00",
	"SerFee": "88.88",
	"Tax": "11.11",
	"Total": "99.99",
	"Note1": "Note Line 1",
	"Note2": "Note Line 2",
	"Note3": "Note Line 3",
	"Rem1": "Remark Line 1",
	"Rem2": "Remark Line 2",
	"Rem3": "Remark Line 3",
	"TaxRate": "5"
*/

// the actual query for the grid data 
//--WORKS-- $SQL = "SELECT TOP 4 Tickets.TicketID,Tickets.Invoice,Tickets.Contact,Tickets.Date,Tickets.PrevBal,Tickets.Tax,Tickets.RouteID FROM Tickets WHERE RouteID=1"; 
//--WORKS-- $SQL = "SELECT TOP 4 Customers.CustID,Customers.Name FROM Customers"; 
//--WORKS-- $SQL = "SELECT DISTINCT D.TicketID, D.RouteID, D.Contact, D1.Name FROM \"Tickets.DB\" D, \"Customers.DB\" D1 WHERE (D.RouteID = 11.0) AND (D1.CustID = D.Contact) ORDER BY D.TicketID, D.RouteID, D.Contact, D1.Name";
//--WORKS-- $SQL = "SELECT DISTINCT D.TicketID, D.RouteID, D.Contact, D.SerTypeID, D1.Name, D1.Address, D1.City, D1.State, D1.Zip FROM \"Tickets.DB\" D, \"Customers.DB\" D1 WHERE (D.RouteID = 11.0) AND (D1.CustID = D.Contact) ORDER BY D.TicketID";
//--WORKS-- 
/*See http://docstore.mik.ua/orelly/webprog/php/ch15_04.htm for info on using Excel*/
$SQL  = "SELECT DISTINCT D.TicketID, D.RouteID, D.SerTypeID, D.SerLocID, D.BillToID, D.Contact";
$SQL .= ", D1.Name AS [ContName], D1.Phone AS [ContPhone]";
$SQL .= ", D2.Name AS [BTName], D2.Address AS [BTAddr], D2.City AS [BTCity], D2.State AS [BTState], D2.Zip AS [BTZip]";
$SQL .= ", D3.Name AS [SLName], D3.Address AS [SLAddr], D3.City AS [SLCity], D3.State AS [SLState], D3.Zip AS [SLZip]";
$SQL .= ", D.Date AS [TicketDate], D.PrevBal, D.SerFee, D.Tax";
$SQL .= ", D.Note1, D.Note2, D.Note3, D.Rem1, D.Rem2, D.Rem3";
$SQL .= " FROM [Tickets$] D, [Customers$] D1, [Customers$] D2,[Customers$] D3";
//$SQL .= " WHERE ((((D.RouteID = ".$RouteID.") AND (D1.CustID = D.Contact)) AND (D2.CustID = D.BillToID)) AND (D3.CustID = D.SerLocID)) ORDER BY D.TicketID ";
//
$SQL .= " WHERE ((((D.TicketID = 583) AND (D1.CustID = D.Contact)) AND (D2.CustID = D.BillToID)) AND (D3.CustID = D.SerLocID)) ORDER BY D.TicketID ";


//-- DEBUG SQL Statement by sending it back.
// echo $SQL."</br></br>"; 
//--WORKS--
//--WORKS--
//--WORKS--
//--BROKE--
echo sql2json($SQL);

error_reporting($olderrorlevel); //put PHP back to old error spew now
?>