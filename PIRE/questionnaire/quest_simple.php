<html>
<head>
<title>Assessing Student Performance for the PIRE Project</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<?php

	$security_domain = "php.scripts.psu.edu"; 
	$url = $_SERVER['HTTP_HOST'];
	
	if (ereg($security_domain, $url)) {

	error_reporting(E_ALL^E_NOTICE);

//	include 'class.ezpdf.php';  // PDF class converter
		
		$process = "basicRTF";
		$_FILENAME_ = $_POST['subj_name'];
		$_PIRENAME_ = $_POST['pire_name'];
		$_FIRSTCHOICE_ = $_POST['first'];
		$_SECCHOICE_ = $_POST['sec'];
		$_THIRDCHOICE_ = $_POST['third'];
		$_FORTHCHOICE_ = $_POST['forth'];
		$_FIFTHCHOICE_ = $_POST['fifth'];
		$_SIXCHOICE_ = $_POST['six'];
		$_FIRSTCOMMENT_ = $_POST['1Comment'];
		$_SECCOMMENT_ = $_POST['2Comment'];
		$_THIRDCOMMENT_ = $_POST['3Comment'];
		$_FORTHCOMMENT_ = $_POST['4Comment'];
		$_FIFTHCOMMENT_ = $_POST['5Comment'];
		$_SIXCOMMENT_ = $_POST['6Comment'];
		$_SEVENCOMMENT_ = $_POST['7Comment'];
		$_EIGHTCOMMENT_ = $_POST['8Comment'];
		$_NINECOMMENT_ = $_POST['9Comment'];

		$_NAMES_= $_FILENAME_; // name with space
		$_FILENAME_ = trim($_FILENAME_);
		$_FILENAME_ = ereg_replace (" ", "", $_FILENAME_);
	
		$pagehead = "PIRE Questionnaire";
		$title = 'Assessing Student Performance for the PIRE Project';
		//echo $_FILENAME_;
	
		// formatting the form input
		$_FILENAME_ = trim($_FILENAME_);		
		$dir_path = "/pass/services/www/dept/cls/PIRE/questionnaire/result/";
		$datetime = date("Ymd-His");
		$txt_file = $dir_path.$_FILENAME_."_".$datetime."_".$_PIRENAME_.".txt";
		$rtf_file = $dir_path.$_FILENAME_."_".$datetime."_".$_PIRENAME_.".rtf";
		$today = getdate();
		//cleanDir($dir_path);
		//authorInfo();
  /* if (isset($_POST['submit'])) { 	// ---- convert to a basic APA rtf -----     
		createRTF($rtf_file,$process);
		createTXT($txt_file);		
		staInfo();
		popup_info();	
//	}   
   else if (isset($_POST['complexRTF'])) { // --- send an email to the investigator -----  	*/
   createRTF($rtf_file,$process);
  createTXT($txt_file);		
  staInfo();
 
$to = "pul8@psu.edu";   
$subject = $_FILENAME_." complete the PIRE questionnaire for ".$_PIRENAME_;    
// ¶¨Òå·Ö½çÏß    
$boundary = "=-v+7WmNkibNKufZ4Od2U1";            //·Ö½çÏßÊÇÒ»´®ÎÞ¹æÂÉµÄ×Ö·û  
  
$filename="result/".$_FILENAME_."_".$datetime."_".$_PIRENAME_.".txt";
$nopathfilename=$_FILENAME_."_".$datetime."_".$_PIRENAME_.".txt";
//¶ÁÈ¡ÉÏ´«ÎÄ¼þ   
$read="Name: ".$_FILENAME_."\n
PIRE Student Name: ".$_PIRENAME_."\n
1.What do you think about the topics of the student's research (e.g., importance, significance)?\n
Choice: ".$_FIRSTCHOICE_."\n
Comment: ".$_FIRSTCOMMENT_."\n
2. What do you think about the degree to which the student's research topic aligns with the goals of research in your lab?\n
Choice: ".$_SECCHOICE_."\n
Comment: ".$_SECCOMMENT_."\n
3. How do you rate the student's involvement in his/her research and interaction with your research group?\n
Choice: ".$_THIRDCHOICE_."\n
Comment: ".$_THIRDCOMMENT_."\n
4. What do you think about the research ethics and work habit of the student (e.g., honest, hard working)?\n
Choice: ".$_FORTHCHOICE_."\n
Comment: ".$_FORTHCOMMENT_."\n
5. How do you rate the overall performance of the student (e.g., integrity, cooperativeness, interaction with others)?\n
Choice: ".$_FIFTHCHOICE_."\n
Comment: ".$_FIFTHCOMMENT_."\n
6. What specific problems did you identify (or dimensions where things could be improved on)?\n
Choice: ".$_SIXCHOICE_."\n
Comment: ".$_SIXCOMMENT_."\n
\n
In order to further enhance our collaborative research, we would like you to provide a few general comments on the following:\n
(1) How might the process be such that not only our students benefit from the visit but your research and your lab also benefit from this process?\n
Comment: ".$_SEVENCOMMENT_."\n
(2) Are there specific ways in which the student visiting process can be changed from its current procedure that you feel would be best in the research and cultural contexts of your institution?\n
Comment: ".$_SEVENCOMMENT_."\n
(3) Are there any other thoughts that you would like to share with us about the PIRE project?\n
Comment: ".$_SEVENCOMMENT_."\n";
//½¨Á¢ÓÊ¼þµÄÖ÷Ìå£¬·ÖÎªÓÊ¼þÄÚÈÝºÍ¸½¼þÄÚÈÝÁ½²¿·Ö  
$body = $read;

/*"--$boundary

".$_FILENAME_." just complete the PIRE questionnaire for assessing student performance. Check out the attachment for the response.

--$boundary
Content-Type: text/plain  
Content-Transfer-Encoding: base64 
Content-Disposition: attachment; filename=$nopathfilename
$read
--".$boundary."--";  */
//ÉèÖÃheader  
$header = "Content-type: multipart/mixed; boundary=\"".$boundary."\"\r\n"; 

//·¢ËÍÓÊ¼þ ²¢Êä³öÊÇ·ñ·¢ËÍ³É¹¦µÄÐÅÏ¢  
if(mail($to, $subject,$body))   
{  
    popup_info();
}  
else   
{  
    echo "Fail, please go back and re-try. Sorry for the inconvenience.";   
}  
//	} else {  
	// error checking - got here by mistake
//		echo "<p>Unexpected error encountered!</p>";
//		exit;
//	}
	
	// --- Illegal usage of script - do not have permission to use ------	
	} else {
		echo "<p>Sorry, you cannot run this script from this location</p>"; 
	} 	

		
	// Sub Functions ------------------------------------
    	function staInfo() {
		global $today,$_FILENAME_,$_PIRENAME_,$rtf_file,$txt_file;
		
		// saving author names and author note
		if(!($fp = fopen("quest_sta.txt", "a"))) {
			echo "<p>Error! Cannot open file...Try again.</p>"; 
			//echo "<p>APA authors info</p>";  // debugging
			exit; 
		}
		if($_NAMES_ != ''){
			fputs($fp, "Subject Name: ".wordwrap($_NAMES_)."\n");
		}	
		fputs($fp, "Date: ".$today["month"].". ".$today["mday"].", ".$today["year"]."\n");
		fputs($fp, "Name: ".$_FILENAME_."\n");
		fputs($fp, "PIRE Student Name: ".$_PIRENAME_."\n");
		fputs($fp, "RTF file: ".wordwrap($rtf_file)."\n");
		fputs($fp, "TXT file: ".wordwrap($txt_file)."\n\n");			
		fclose($fp);	
	}
	
	function popup_info() {
		global $rtf_file,$txt_file;
		echo '<html><head>
		</head>
		<body>
		<form action="request_file.php" method="POST" name="request_file" target="_new" id="request_file">
		<input type="hidden" name="subj_file" value="',$rtf_file,'">
		<input type="hidden" name="subj_loc" value="',$_SERVER['REMOTE_ADDR'],'">
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p align="center"> Thank you for completing the questionnaire. <br> 
		<br>  
		You can <input name="submit_request" type="submit" id="submit_request" value="Download"> the results of your completed form. </form><br>
		You then can print out your results or save them as a file.</p>
		<p>&nbsp;</p>
		</body></html>';
	}
	

	// --------------------------------- RTF ------------------------------------------------
		function createRTF($data_file,$process_type) {
	    global $_FILENAME_,$_PIRENAME_, $_FIRSTCHOICE_, $_FIRSTCOMMENT_, $_SECCHOICE_, $_SECCOMMENT_, $_THIRDCHOICE_, $_THIRDCOMMENT_, $_FORTHCHOICE_, $_FORTHCOMMENT_, $_FIFTHCHOICE_, $_FIFTHCOMMENT_, $_SIXCHOICE_, $_SIXCOMMENT_, $_SEVENCOMMENT_, $_EIGHTCOMMENT_, $_NINECOMMENT_;
		global $pagehead,$title,$today;
		
		$header = "{\\rtf1\ansi\deff0 {\fonttbl {\f0 Times New Roman;}} ";
		$format = "\ftnbj\ftnrestart\deflang1033\windowctrl ";
		$margins = "{\margl1440\margr1440\margt1440\margb1440}"; 
		$page_num = "{\header\pard\qr\plain\f0 {\\noproof ".$pagehead."    }\chpgn\par} ";
		$text = "\f0\fs24\ ";
		$end_file = " }";
		$title_page = "{\\titlepg";
		
		// APA Style - Title page -------------
		$title_page = $title_page."\pard\sb100\sl480\slmult1\ql Running Head: ".strtoupper($pagehead)."\par";
		$title_page = $title_page."\pard\sb4500\sl480\slmult1\qc ".wordwrap($title,50)."\par";
        $title_page = $title_page."\pard\sl480\slmult1\qc Completed by: ".$_FILENAME_."\par";
		$title_page = $title_page."\pard\sl480\slmult1\qc Date: ".$today["month"].". ".$today["mday"].", ".$today["year"]."\par";
		$title_page = $title_page."\page}";
						
		// write to rtf file
		if(!($fh = fopen($data_file, "w"))) {
			echo "<p>Error! Cannot open file...Try again.</p>"; 
			echo "<p>RTF process data ",$data_file,"</p>";  //debugging
			exit; 
		}
		fputs($fh, $header);
		fputs($fh, $format);
		fputs($fh, $margins);
		fputs($fh, $page_num);
		fputs($fh, $text);

		fputs($fh, "{\pard\sl480\slmult1\qc {\b ".wordwrap($title,50)." }\par}");
		fputs($fh, "\pard\sl480\slmult1\qc Date: ".$today["month"].". ".$today["mday"].", ".$today["year"]."\par");
        fputs($fh, "\pard\sl480\slmult1\qc   \par");
		fputs($fh, "{\pard\sl480\slmult1\ql Name: ".$_FILENAME_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql PIRE Student Name: ".$_PIRENAME_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql Please evaluate the student along the following (give the rating first, then your comments):\par");
		fputs($fh, "\pard\sl480\slmult1\ql 1.What do you think about the topics of the student's research (e.g., importance, significance)?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Choice: ".$_FIRSTCHOICE_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_FIRSTCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql 2. What do you think about the degree to which the student's research topic aligns with the goals of research in your lab?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Choice: ".$_SECCHOICE_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_SECCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql 3. How do you rate the student's involvement in his/her research and interaction with your research group?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Choice: ".$_THIRDCHOICE_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_THIRDCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql 4. What do you think about the research ethics and work habit of the student (e.g., honest, hard working)?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Choice: ".$_FORTHCHOICE_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_FORTHCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql 5. How do you rate the overall performance of the student (e.g., integrity, cooperativeness, interaction with others)?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Choice: ".$_FIFTHCHOICE_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_FIFTHCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql 6. What specific problems did you identify (or dimensions where things could be improved on)?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Choice: ".$_SIXCHOICE_."\par}");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_SIXCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\qc   \par");
		fputs($fh, "\pard\sl480\slmult1\ql In order to further enhance our collaborative research, we would like you to provide a few general comments on the following:\par");
		fputs($fh, "\pard\sl480\slmult1\ql (1) How might the process be such that not only our students benefit from the visit but your research and your lab also benefit from this process?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_SEVENCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql (2) Are there specific ways in which the student visiting process can be changed from its current procedure that you feel would be best in the research and cultural contexts of your institution?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_EIGHTCOMMENT_."\par}");
		fputs($fh, "\pard\sl480\slmult1\ql (3) Are there any other thoughts that you would like to share with us about the PIRE project?\par");
		fputs($fh, "{\pard\sl480\slmult1\ql Comment: ".$_NINECOMMENT_."\par}");
    	fputs($fh, "{\pard\\sb500\sl480\slmult1\qc {\b *******************************END*******************************} \par}");		
		fputs($fh, $end_file);
		fclose($fh);

	
		
	}
	
	// --------------------------------- TXT ------------------------------------------------
	function createtxt($data_file) {
	    global $_FILENAME_,$_PIRENAME_,  $_FIRSTCHOICE_, $_FIRSTCOMMENT_, $_SECCHOICE_, $_SECCOMMENT_, $_THIRDCHOICE_, $_THIRDCOMMENT_, $_FORTHCHOICE_, $_FORTHCOMMENT_, $_FIFTHCHOICE_, $_FIFTHCOMMENT_, $_SIXCHOICE_, $_SIXCOMMENT_, $_SEVENCOMMENT_, $_EIGHTCOMMENT_, $_NINECOMMENT_;
		global $pagehead,$title,$today;

		// write to rtf file
		if(!($fh = fopen($data_file, "w"))) {
			echo "<p>Error! Cannot open file...Try again.</p>"; 
			//echo "<p>RTF process data</p>";  //debugging
			exit; 
		}

		fputs($fh, "Name: ".$_FILENAME_."\n");
		fputs($fh, "PIRE Student Name: ".$_PIRENAME_."\n\n");
		fputs($fh, "1.What do you think about the topics of the student's research (e.g., importance, significance)?\n");
		fputs($fh, "Choice: ".$_FIRSTCHOICE_."\n");
		fputs($fh, "Comment: ".$_FIRSTCOMMENT_."\n");
		fputs($fh, "2. What do you think about the degree to which the student's research topic aligns with the goals of research in your lab?\n");
		fputs($fh, "Choice: ".$_SECCHOICE_."\n");
		fputs($fh, "Comment: ".$_SECCOMMENT_."\n");
		fputs($fh, "3. How do you rate the student's involvement in his/her research and interaction with your research group?\n");
		fputs($fh, "Choice: ".$_THIRDCHOICE_."\n");
		fputs($fh, "Comment: ".$_THIRDCOMMENT_."\n");
		fputs($fh, "4. What do you think about the research ethics and work habit of the student (e.g., honest, hard working)?\n");
		fputs($fh, "Choice: ".$_FORTHCHOICE_."\n");
		fputs($fh, "Comment: ".$_FORTHCOMMENT_."\n");
		fputs($fh, "5. How do you rate the overall performance of the student (e.g., integrity, cooperativeness, interaction with others)?\n");
		fputs($fh, "Choice: ".$_FIFTHCHOICE_."\n");
		fputs($fh, "Comment: ".$_FIFTHCOMMENT_."\n");
		fputs($fh, "6. What specific problems did you identify (or dimensions where things could be improved on)?\n");
		fputs($fh, "Choice: ".$_SIXCHOICE_."\n");
		fputs($fh, "Comment: ".$_SIXCOMMENT_."\n");
		fputs($fh, "\n");
		fputs($fh, "In order to further enhance our collaborative research, we would like you to provide a few general comments on the following:\n");
		fputs($fh, "(1) How might the process be such that not only our students benefit from the visit but your research and your lab also benefit from this process?\n");
		fputs($fh, "Comment: ".$_SEVENCOMMENT_."\n");
		fputs($fh, "(2) Are there specific ways in which the student visiting process can be changed from its current procedure that you feel would be best in the research and cultural contexts of your institution?\n");
		fputs($fh, "Comment: ".$_SEVENCOMMENT_."\n");
		fputs($fh, "(3) Are there any other thoughts that you would like to share with us about the PIRE project?\n");
		fputs($fh, "Comment: ".$_SEVENCOMMENT_."\n");
		
		
		fclose($fh);
	}	
?> 
</body>
</html>
