<?php
//Import Student Data
 if(!isset($_POST['Submit']))
  header ('Location: ./');
?>
<?php include_once('header.php'); ?>

<div class="page_content">
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-12  col-md-12 col-lg-12">
      <table class="table table-bordered tabcenter table-striped">
        <tr>
          <td><strong>ID</strong></td>
          <td><strong>Year of Admission</strong></td>
          <td><strong>Semester</strong></td>
          <td><strong>Batch</strong></td>
          <td><strong>Department</strong></td>
          <td><strong>Name</strong></td>
          <td><strong>Roll Number</strong></td>
          <td><strong>Error</strong></td>
        </tr>
        <?php
        include('config.php');
	$Counter=0;
	$FileUploadPath= "uploads/";
	 if ((($_FILES["Student-Data-CSV"]["type"] == "application/vnd.ms-excel" || $_FILES["Student-Data-CSV"]["type"] =="text/csv"))
&& ($_FILES["Student-Data-CSV"]["size"] < 20000000))
  {
  if ($_FILES["Student-Data-CSV"]["error"] > 0)
    {
    $result['response']= "Return Code: " . $_FILES["Student-Data-CSV"]["error"] . "<br />";
    }else{
      $FileName = rand(112255,11556554).'.'.pathinfo($_FILES['Student-Data-CSV']['name'],PATHINFO_EXTENSION);
      move_uploaded_file($_FILES["Student-Data-CSV"]["tmp_name"],
      $FileUploadPath . $FileName);
	  $FileName = $FileUploadPath . $FileName;
	  echo $FileName;
	 $result['response']= "Success";
    echo "success";
	  }
    }else{
    echo "invalid file";
	//$result['response']= "Invalid File";
    }
	$file = fopen($FileName,"r");
	$data = fgetcsv($file);
	while(!feof($file))
	{
		$Counter++;
		$data = fgetcsv($file);
		$yoa = $data[0];
		$sem = trim($data[1]);
		$batch = trim($data[2]);
		$admn = trim($data[3]);
		$roll = trim($data[4]);
		$uid = trim($data[5]);
		$name = trim($data[6]);
		$sex = trim($data[7]);
		$dept = substr($uid,0,2);
		$SQL = mysqli_query($conn, "INSERT into student_data (yoa,sem,department,batch,admn,roll,uid,name,sex) values('$yoa','$sem','$dept','$batch','$admn','$roll','$uid','$name','$sex')");
		if($SQL)
		 {
		  echo '<tr>
          <td>'.$Counter.'</td>
          <td>'.$yoa.'</td>
          <td>'.$sem.'</td>
          <td>'.$batch.'</td>
          <td>'.$dept.'</td>
          <td>'.$name.'</td>
          <td>'.$roll.'</td>
          <td>Success</td>
          </tr>';
		 }
		else
		{
		  echo '<tr>
          <td>'.$Counter.'</td>
          <td>'.$yoa.'</td>
          <td>'.$sem.'</td>
          <td>'.$batch.'</td>
          <td>'.$dept.'</td>
          <td>'.$name.'</td>
          <td>'.$roll.'</td>
          <td>'.mysqli_error().'</td>
          </tr>';
		}
	}

// check if form is submitted
/*include('config.php');
if(isset($_POST["Import"])){
   $created_at = date('Y-m-d h:i:s'); // to keep track when record is created
   $filename = $_FILES["file"]["tmp_name"]; // get temporary CSV file name

   // check if file is not empty
   if($_FILES["file"]["size"] > 0){
      // open and read the file
      $file = fopen($filename, "r");

      // define variables for tracking/stats
      $success = 0; $error = 0;

      // recursively iterate through each row of data
      while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
         // fetch data based on indexes
         $Counter++;
         $yoa = $getData[0];
         $sem = $getData[1];
         $batch = $getData[2];
         $admn = $getData[3];
         $roll = $getData[4];
         $uid = $getData[5];
         $name = $getData[6];
         $sex = $getData[7];
         $dept = $getData[8];

         // the amount is integer, set it to 0 as default if found empty in CSV
         if ($getData[10] != '' ) {
            $amount = $getData[10];
         }else{
            $amount = 0;
         }

         // create MySQL insert statement
         $sql = "INSERT into student_data (yoa,sem,department,batch,admn,roll,uid,name,sex) values('$yoa','$sem','$dept','$batch','$admn','$roll','$uid','$name','$sex')";

         // execute the above created SQL
         if(mysqli_query($conn, $sql)) {
            $success++; // count inserted records
            echo $success." records has been imported successfully.";
            echo '<tr>
                <td>'.$Counter.'</td>
                <td>'.$yoa.'</td>
                <td>'.$sem.'</td>
                <td>'.$batch.'</td>
                <td>'.$dept.'</td>
                <td>'.$name.'</td>
                <td>'.$roll.'</td>
                <td>Success</td>
                </tr>';
         }
         else {
            $error++; // count rejected records

            // default 1 error for CSV headers
            if($error > 1 && ACCEPT_CSV_HEADERS == true){
               echo $error." records didn't imported because of format mismatch.";
            }
         }
      }

      // close the CSV file
      fclose($file);
   }
}*/

/*include('config.php');

$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $yoa = $line[0];
                $sem = $line[1];
                $batch = $line[2];
                $admn = $line[3];
                $roll = $line[4];
                $uid = $line[5];
                $name = $line[6];
                $sex = $line[7];
                $dept = $line[8];

                // Check whether member already exists in the database with the same email
                //$prevQuery = "SELECT id FROM members WHERE email = '".$line[1]."'";
                $prevResult = $conn->query();

                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $conn->query("UPDATE student_data SET yoa = '".$yoa."', sem = '".$sem."', batch = '".$batch."', admn = '".$admn."', roll = '".$roll."', uid = '".$uid."', name = '".$name."', sex = '".$sex."', department = '".$dept."'");
                    echo '<tr>
                        <td>'.$Counter.'</td>
                        <td>'.$yoa.'</td>
                        <td>'.$sem.'</td>
                        <td>'.$batch.'</td>
                        <td>'.$dept.'</td>
                        <td>'.$name.'</td>
                        <td>'.$roll.'</td>
                        <td>Success</td>
                        </tr>';
                }else{
                    // Insert member data in the database
                    $conn->query("INSERT into student_data (yoa,sem,department,batch,admn,roll,uid,name,sex) values('$yoa','$sem','$dept','$batch','$admn','$roll','$uid','$name','$sex')");
                    echo '<tr>
                        <td>'.$Counter.'</td>
                        <td>'.$yoa.'</td>
                        <td>'.$sem.'</td>
                        <td>'.$batch.'</td>
                        <td>'.$dept.'</td>
                        <td>'.$name.'</td>
                        <td>'.$roll.'</td>
                        <td>Success</td>
                        </tr>';
                }
            }

            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }*/

	?>
      </table>
    </div>
  </div>
</div>
<?php include_once('footer.php');
