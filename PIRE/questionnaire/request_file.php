<html>

<?php


//DEBUG TO SHOW ALL VARIABLES STORED IN POST
//echo "<table border=1><tr><th>Parameter Name</th><th>Value</th></tr>";
//foreach ( $_POST as $param_name => $value ) {
//    echo "<tr> <td>" . htmlspecialchars($param_name) . "</td> <td>" . htmlspecialchars($value) . "</td> </tr>\n";
//}
//echo "</table>";

$subj_addr = $_POST["subj_loc"];
if ($_SERVER['REMOTE_ADDR'] == $subj_addr) {
	//Serve the file only if requester's IP address matches the address passed by the survey
	$file = $_POST['subj_file'];
		if (file_exists($file)) {
   			header('Content-Description: File Transfer');
   			header('Content-Type: application/octet-stream');
    			header('Content-Disposition: attachment; filename='.basename($file));
    			header('Content-Transfer-Encoding: binary');
    			header('Expires: 0');
    			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    			header('Pragma: public');
    			header('Content-Length: ' . filesize($file));
    			ob_clean();
    			flush();
    			readfile($file);
    			exit;
		} else {
			//If file does not exist, say so.
			echo "<body>Cannot locate your file.</body>";
		}
} else {
	//If requester's IP does not match, just claim that the file does not exist.
	//This measure makes it harder for requesters to fish for files that might 
	//exist by requesting them and being explicitly denied.  Protects participant
	//identity.
	echo "<body>";
	echo "Cannot locate your file.</body>";
}

?>
</html>
