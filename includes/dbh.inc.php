<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "summies";


$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);


if(!$conn){
    die("Sorry, connection could not be established: ".mysqli_connect_error());
}