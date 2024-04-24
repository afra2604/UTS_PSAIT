<?php
require_once "config.php";
$request_method=$_SERVER["REQUEST_METHOD"];
switch ($request_method) {
   case 'GET':
         if(!empty($_GET["nim"]))
         {
            $nim=($_GET["nim"]);
            get_perkuliahan($nim);
         }
         else
         {
            get_perkuliahann();
         }
         break;
   case 'POST':
         if(!empty($_GET["nim"]) && !empty($_GET["kode_mk"]))
         {
            $nim=strval($_GET["nim"]);
            $kode_mk=strval($_GET["kode_mk"]);
            update_mk($nim, $kode_mk);
         }
         else
         {
            insert_mk();
         }     
         break; 
   case 'DELETE':
          $id=strval($_GET["nim"]);
          $kode_mk=strval($_GET["kode_mk"]);
          
            delete_mk($id, $kode_mk);
            break;
   default:
      // Invalid Request Method
         header("HTTP/1.0 405 Method Not Allowed");
         break;
      break;
 }



 function get_perkuliahann()
 {
    global $mysqli;
    $query="SELECT p.*, m.nama FROM perkuliahan p JOIN mahasiswa m ON p.nim = m.nim";
    $data=array();
    $result=$mysqli->query($query);
    while($row=mysqli_fetch_object($result))
    {
       $data[]=$row;
    }
    $response=array(
                   'status' => 1,
                   'message' =>'Get List nilai mahasiswa  Successfully.',
                   'data' => $data
                );
    header('Content-Type: application/json');
    echo json_encode($response);
 }
 
   function get_perkuliahan($nim)
   {
      global $mysqli;
      $query="SELECT * FROM perkuliahan WHERE nim='$nim'";
      $data=array();
      $result=$mysqli->query($query);
      while($row=mysqli_fetch_assoc($result))
      {
         $data[]=$row;
      }
      $response=array(
                     'status' => 1,
                     'message' =>'Get nilai mahasiswa with id Successfully.',
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
        
   }
 
   function insert_mk()
      {
         global $mysqli;
         if(!empty($_POST["nama"])){
            $data=$_POST;
         }else{
            $data = json_decode(file_get_contents('php://input'), true);
         }

         $arrcheckpost = array('nim' => '','kode_mk' => '', 'nilai' => '');
         $hitung = count(array_intersect_key($data, $arrcheckpost));
         if($hitung == count($arrcheckpost)){
          
               $result = mysqli_query($mysqli, "INSERT INTO perkuliahan SET
               nim = '$data[nim]',
               kode_mk = '$data[kode_mk]',
               nilai = '$data[nilai]'");                
               if($result)
               {
                  $response=array(
                     'status' => 1,
                     'message' =>'Nilai Mahasiswa Added Successfully.'
                  );
               }
               else
               {
                  $response=array(
                     'status' => 0,
                     'message' =>'Nilai Mahasiswa Addition Failed.'
                  );
               }
         }else{
            $response=array(
                     'status' => 0,
                     'message' =>'Parameter Do Not Match'
                  );
         }
         header('Content-Type: application/json');
         echo json_encode($response);
      }
 
   function update_mk($nim, $kode_mk)
      {
         global $mysqli;
         if(!empty($_POST["nama"])){
            $data=$_POST;
         }else{
            $data = json_decode(file_get_contents('php://input'), true);
         }

         $arrcheckpost = array('nim' => '','kode_mk' => '', 'nilai' => '');
         $hitung = count(array_intersect_key($data, $arrcheckpost));
         if($hitung == count($arrcheckpost)){
          
              $result = mysqli_query($mysqli, "UPDATE mahasiswa SET
              nim='$data[nim]',
              kode_mk='$data[kode_mk]',
              nilai='$data[nilai]'
              WHERE id_mhs='$nim' AND kode_mk='$kode_mk");
          
            if($result)
            {
               $response=array(
                  'status' => 1,
                  'message' =>'Nilai Mahasiswa Updated Successfully.'
               );
            }
            else
            {
               $response=array(
                  'status' => 0,
                  'message' =>'Nilai Mahasiswa Updation Failed.'
               );
            }
         }else{
            $response=array(
                     'status' => 0,
                     'message' =>'Parameter Do Not Match'
                  );
         }
         header('Content-Type: application/json');
         echo json_encode($response);
      }
 
   function delete_mk($id)
   {
      global $mysqli;
      $query="DELETE FROM perkuliahan WHERE id_perkuliahan=".$id;
      if(mysqli_query($mysqli, $query))
      {
         $response=array(
            'status' => 1,
            'message' =>'Nilai Mahasiswa Deleted Successfully.'
         );
      }
      else
      {
         $response=array(
            'status' => 0,
            'message' =>'Nilai Mahasiswa Deletion Failed.'
         );
      }
      header('Content-Type: application/json');
      echo json_encode($response);
   }

 
?>