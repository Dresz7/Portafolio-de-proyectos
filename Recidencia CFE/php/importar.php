<?php

function restoreDatabaseTables($dbHost, $dbUsername, $dbPassword, $dbName, $filePath){
    // Connect & select the database
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 


    // Temporary variable, used to store current query
    $templine = '';
    
    // Read in entire file
    $lines = file($filePath);
    
    $error = '';
    
    // Loop through each line
    foreach ($lines as $line){
        // Skip it if it's a comment
        if(substr($line, 0, 2) == '--' || $line == ''){
            continue;
        }
        
        // Add this line to the current segment
        $templine .= $line;
        
        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';'){
            // Perform the query
            if(!$db->query($templine)){
                $error .= 'Error performing query "<b>' . $templine . '</b>": ' . $db->error . '<br /><br />';
            }
            
            // Reset temp variable to empty
            $templine = '';
        }
    }
    return !empty($error)?$error:true;
}
    
    // if(isset($_POST['importar'])){
        
        //get post data
        
 
        //moving the uploaded sql file
        $filename = $_FILES['sql']['name'];
        move_uploaded_file($_FILES['sql']['tmp_name'],$filename);
        $file_location = $filename;
        
        $dbHost     = 'localhost';
        $dbUsername = 'root';
        $dbPassword = 'DreszSeven123';
        $dbName     = 'cfe';
        $filePath   = 'cfe_2022-11-06_20-29-18_.sql';

        include("../conection/conexion.php");
        //borrar base
        $tborrar="DROP DATABASE $dbName;";
        $borrar=mysqli_query($conn, $tborrar);

        //Volver a crear
        $tcrear="CREATE DATABASE $dbName;";
        $crear=mysqli_query($conn, $tcrear);
        
        //restore database using our function
        $restore = restoreDatabaseTables($dbHost, $dbUsername, $dbPassword, $dbName, $file_location);

        echo $restore;
    // }
    // else{
    //     echo "Fallo";
    // }
 
?>