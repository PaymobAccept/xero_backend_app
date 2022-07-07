<?php
include('db.php');
$file = fopen("Journal_Examples.csv","r");
$data = array();
$i =1;
while(($line = fgetcsv($file)) !== FALSE){ 
                // Get row data
                // $data = array(
                //     'MonthDate' =>  '"'.$line[0].'"',
                //      'Reference' => '"'.$line[1].'"' ,
                //      'GLAccount' => '"'.$line[2].'"' , 
                //      'AccountDesc' => '"'.$line[3].'"' ,
                //      'Debit' =>   '"'.$line[4].'"' ,
                //      'Credit' =>'"'.$line[5].'"' ,
                //      'Type' =>'"'.$line[6].'"' ,
                //     'Subsidiary' =>'"'.$line[7].'"' ,
                //     'PaymentDueDate' =>'"'.$line[8].'"' ,
                //     'Location' =>'"'.$line[9].'"' ,
                //     );
                   $data[] = "('','$line[0]', '$line[1]', '$line[2]','$line[3]', '$line[4]', '$line[5]','$line[6]', '$line[7]', '$line[8]', '$line[9]')";
 }
 unset($data[0]);
 
 $dbc = new db();
 //$escaped_values = array_map('mysql_real_escape_string', array_values($data));
$values .= implode(',', $data);
  
$appDetail = $dbc->execute("INSERT INTO  journal VALUES $values");

               echo '<pre>hrt';print_r($data);  
// while(! feof($file))
//   {
//   echo '<pre>';print_r(fgetcsv($file));
//   }

fclose($file);
?>