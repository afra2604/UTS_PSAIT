<?php
require_once "config.php";
$request_method=$_SERVER["REQUEST_METHOD"];
switch ($request_method) {
   case 'GET':
         if(!empty($_GET["nim"]))
         {
            $nim=($_GET["nim"]);
            get_mhs($nim);
         }
         else
         {
            get_mhss();
         }
         break;
   case 'POST':
      $input = json_decode(file_get_contents('php://input'), true);
      if (!empty($input["nim"]) && !empty($input["kode_mk"]) && isset($input["nilai"])) {
          $nim = $input["nim"];
          $kode_mk = $input["kode_mk"];
          $nilai = $input["nilai"];
          insert_nilai($nim, $kode_mk, $nilai);
      } else {
          header("HTTP/1.0 400 Bad Request");
          echo json_encode(array("status" => 0, "message" => "Missing parameters"));
      }
      break;
   case 'PUT':
      $input = json_decode(file_get_contents('php://input'), true);
      if (!empty($_GET["nim"]) && !empty($_GET["kode_mk"]) && isset($input["nilai"])) {
            $nim = $_GET["nim"];
            $kode_mk = $_GET["kode_mk"];
            $nilai = $input["nilai"];
            update_nilai($nim, $kode_mk, $nilai);
      } else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(array("status" => 0, "message" => "Missing parameters"));
      }
      break;
      case 'DELETE':
         if (!empty($_GET["nim"]) && !empty($_GET["kode_mk"])) {
             $nim = $_GET["nim"];
             $kode_mk = $_GET["kode_mk"];
             delete_nilai($nim, $kode_mk);
         } else {
             header("HTTP/1.0 400 Bad Request");
             echo json_encode(array("status" => 0, "message" => "Missing parameters"));
         }
         break;
   default:
      // Invalid Request Method
         header("HTTP/1.0 405 Method Not Allowed");
         break;
 }



   function get_mhss()
   {
      global $mysqli;
      // nama, mk ,nilai
      $query="SELECT p.*, m.nama FROM perkuliahan p JOIN mahasiswa m ON p.nim = m.nim";
      $data=array();
      $result=$mysqli->query($query);
      while($row=mysqli_fetch_object($result))
      {
         $data[]=$row;
      }
      $response=array(
                     'status' => 1,
                     'message' =>'Get List Mahasiswa Successfully.',
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
   }
 
   function get_mhs($nim = "")   
   {
    global $mysqli;
    $query = "SELECT m.nama AS 'Nama Mahasiswa', mk.nama_mk AS 'Mata Kuliah', p.nilai AS 'Nilai'
              FROM perkuliahan p
              JOIN mahasiswa m ON p.nim = m.nim
              JOIN matakuliah mk ON p.kode_mk = mk.kode_mk
              WHERE m.nim = '$nim';";

    $data = array();
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
        $data[] = $row;
    }

    if (empty($data)) {
        $response = array(
            'status' => 0,
            'message' => 'No data found for the specified NIM.',
            'data' => $data
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Get Mahasiswa Successfully.',
            'data' => $data
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
   }
 
   function insert_nilai($nim="", $kode_mk="", $nilai=0)
   // nim, kode mk, nilai
      {
         global $mysqli;
         echo "nim: $nim, kode_mk: $kode_mk, nilai: $nilai";
         $query = "INSERT INTO `perkuliahan` (`nim`, `kode_mk`, `nilai`) VALUES ('$nim', '$kode_mk', $nilai)";
         if ($mysqli->query($query)) {
             echo json_encode(array("status" => 1, "message" => "Nilai added successfully"));
         } else {
             header('Content-Type: application/json');
             echo json_encode(array("status" => 0, "message" => "Failed to insert data: " . $mysqli->error));
         }
      }
 
      function update_nilai($nim, $kode_mk, $nilai)
      {
          global $mysqli;
          $query = "UPDATE perkuliahan SET nilai=$nilai 
                    WHERE nim='$nim' AND kode_mk='$kode_mk'";
          if ($mysqli->query($query)) {
              echo json_encode(array("status" => 1, "message" => "Data updated successfully"));
          } else {
              header("HTTP/1.0 500 Internal Server Error");
              echo json_encode(array("status" => 0, "message" => "Failed to update data"));
          }
      }
 
      function delete_nilai($nim, $kode_mk)
      {
          global $mysqli;
          $query = "DELETE FROM perkuliahan 
                    WHERE nim='$nim' AND kode_mk='$kode_mk'";
          if ($mysqli->query($query)) {
              echo json_encode(array("status" => 1, "message" => "Data deleted successfully"));
          } else {
              header("HTTP/1.0 500 Internal Server Error");
              echo json_encode(array("status" => 0, "message" => "Failed to delete data"));
          }
      }
   
?> 
