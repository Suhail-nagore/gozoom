<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $angularName = $_POST["angularName"];
    $angularBusiness = $_POST["angularBusiness"];
    $angularEmail = $_POST["angularEmail"];
    $angularPhoneNumber = $_POST["angularPhoneNo"];
    $angularSubject = $_POST["angularSubject"];
    $angularTextArea = $_POST["angularTextArea"];

    $servername = "localhost"; // Replace with your MySQL server name
    $username = "gozoomte_form123"; // Replace with your MySQL username
    $password = "Gozoom@123"; // Replace with your MySQL password
    $dbname = "gozoomte_form"; // Replace with your MySQL database name


    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Connection failed: " . $e->getMessage();
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO angularform (name, bussiness, email, phoneNo, subject, textArea) VALUES (:angularName, :angularBusiness, :angularEmail, :angularPhoneNo, :angularSubject, :angularTextArea)");
        $stmt->bindParam(':angularName', $angularName);
        $stmt->bindParam(':angularBusiness', $angularBusiness);
        $stmt->bindParam(':angularEmail', $angularEmail);
        $stmt->bindParam(':angularPhoneNo', $angularPhoneNumber);
        $stmt->bindParam(':angularSubject', $angularSubject);
        $stmt->bindParam(':angularTextArea', $angularTextArea);
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle database query error
        echo "Error: " . $e->getMessage();
        exit;
    }

     // Close the database connection
     $conn = null;
     header("Location: success.html");
     exit;

     // Send an email with the form data
    // Send an email with the form data
//  $mail = new PHPMailer(true);
 
//  try {
//      // Server settings
//      $mail->isSMTP();
//      $mail->Host = 'mail.gozoomtech.com'; // Replace with your SMTP server address
//      $mail->SMTPAuth = true;
//      $mail->Username = 'info@gozoomtech.com'; // Replace with your SMTP username
//      $mail->Password = 'Gozoom@123'; // Replace with your SMTP password
//      $mail->SMTPSecure = 'tls'; // Enable TLS encryption, 'ssl' also possible
//      $mail->Port = 587;
 
    //  // Recipients
    //  $mail->setFrom($email);
    //  $mail->addAddress('info@gozooomtech.com'); // Replace with the desired email address
 
    //  // Email content
    //  $mail->isHTML(false);
    //  $mail->Subject = 'Form Submission';
    //  $mail->Body = "Name: $angularName\n"
    //      . "Bussiness: $angularBussiness\n"
    //      . "Email: $angularEmail\n"
    //      . "Phoneno: $angularPhoneNumber\n"
    //      . "Subject: $angularSubject\n"
    //      . "Enquiry: $angularTextArea\n"
 
    //  $mail->send();
 
    //  // Email sent successfully
    //  header("Location: success.html");
    //  exit;
//  } catch (Exception $e) {
//      // Error sending email
//      echo "Error sending email: " . $mail->ErrorInfo;
//      exit;
//  }
 
 }
 
 ?>