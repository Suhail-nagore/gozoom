<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$recaptcha_secret = "6Lf9TsQnAAAAANxF0lHRxZTC_YMZIMmV1qX1v1qs"; // Replace with your secret key
$recaptcha_response = $_POST['g-recaptcha-response'];

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
$responseKeys = json_decode($response, true);





if(intval($responseKeys["success"]) !== 1) {
    echo "reCAPTCHA verification failed.";
} else {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $contactNumber = $_POST["contact-number"];
    $solution = $_POST["solution"];
    $message = $_POST["message"];

    // Validate and sanitize the form data (perform necessary checks)
    if (isset($_POST["hidden1-input1"]) && !empty($_POST["hidden1-input1"])){
            echo "SPAM!!";
            die();
        }

    

    // Connect to the database
    $servername = "localhost"; // Replace with your MySQL server name
    $username = "gozoomte_form123"; // Replace with your MySQL username
    $password = "Gozoom@123"; // Replace with your MySQL password
    $dbname = "gozoomte_form"; // Replace with your MySQL database name

    // Create a new PDO instance
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Connection failed: " . $e->getMessage();
        exit;
    }

    // Prepare and execute the database query
    try {
        $stmt = $conn->prepare("INSERT INTO enquiry (name, email, contact_number, solution, message) VALUES (:name, :email, :contactNumber, :solution, :message)ON DUPLICATE KEY UPDATE name='$solution'");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contactNumber', $contactNumber);
        $stmt->bindParam(':solution', $solution);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle database query error
        echo "Error: " . $e->getMessage();
        exit;
    }

    // Close the database connection
    $conn = null;

    // Send an email with the form data
   // Send an email with the form data
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.gozoomtech.com'; // Replace with your SMTP serveraddress
    $mail->SMTPAuth = true;
    $mail->Username = 'info@gozoomtech.com'; // Replace with your SMTP username
    $mail->Password = 'Gozoom@123'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, 'ssl' also possible
    $mail->Port = 587;

    // Recipients
    $mail->setFrom($email,$name);
    $mail->addAddress('info@gozoomtech.com'); // Replace with the desired email address

    // Email content
    $mail->isHTML(false);
    $mail->Subject = 'Form Submission';
    $mail->Body = "Name: $name\n"
        . "Email: $email\n"
        . "Contact Number: $contactNumber\n"
        . "Solution: $solution\n"
        . "Message: $message\n";

    $mail->send();
    // print_r($mail);
    // echo 'Mail SEnt';

    // Email sent successfully
    header("Location: success.html");
    exit;
} catch (Exception $e) {
    // Error sending email
    echo "Error sending email: " . $mail->ErrorInfo;
    exit;
}


}
}


?>
