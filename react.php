<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reactName = $_POST["reactName"];
    $reactEmail = $_POST["reactEmail"];
    $reactPhoneNumber = $_POST["reactPhoneNo"];
    $reactSubject = $_POST["reactSubject"];
    $reactTextArea = $_POST["reactTextArea"];

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
        $stmt = $conn->prepare("INSERT INTO reactform (name, email, phoneNo, subject, textArea) VALUES (:reactName, :reactEmail, :reactPhoneNo, :reactSubject, :reactTextArea)");
        $stmt->bindParam(':reactName', $reactName);
        $stmt->bindParam(':reactEmail', $reactEmail);
        $stmt->bindParam(':reactPhoneNo', $reactPhoneNumber);
        $stmt->bindParam(':reactSubject', $reactSubject);
        $stmt->bindParam(':reactTextArea', $reactTextArea);
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
//     // Send an email with the form data
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
 
//      // Recipients
//      $mail->setFrom($email);
//      $mail->addAddress('info@gozooomtech.com'); // Replace with the desired email address
 
//      // Email content
//      $mail->isHTML(false);
//      $mail->Subject = 'Form Submission';
//      $mail->Body = "Name: $reactName\n"
//          . "Email: $reactEmail\n"
//          . "Phoneno: $reactPhoneNumber\n"
//          . "Subject: $reactSubject\n"
//          . "Company Name: $reactTextArea\n"
 
//      $mail->send();
 
//      // Email sent successfully
//      header("Location: success.html");
//      exit;
//  } catch (Exception $e) {
//      // Error sending email
//      echo "Error sending email: " . $mail->ErrorInfo;
//      exit;
//  }
 
 }
 
 ?>