<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$recaptcha_secret = "6Lf9TsQnAAAAAAyZiEcU4rEDn7fVGOGKr7kISjBn"; // Replace with your secret key
$recaptcha_response = $_POST['g-recaptcha-response'];

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) !== 1) {
    echo "reCAPTCHA verification failed.";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["full-name"];
        $contactNumber = $_POST["phone"];
        $email = $_POST["email"];
        $message = $_POST["message"];

        // Validate and sanitize the form data (perform necessary checks)
        if (isset($_POST["hidden1-input1"]) && !empty($_POST["hidden1-input1"])) {
            echo "SPAM!!";
            die();
        }

        // Connect to the database
        $servername = "localhost"; // Replace with your MySQL server name
        $username = "gozoomte_form123"; // Replace with your MySQL username
        $password = "Gozoom@123"; // Replace with your MySQL password
        $dbname = "gozoomte_form"; // Replace with your MySQL database name

        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute the database query
            $stmt = $conn->prepare("INSERT INTO popup_form (name, contact_number, email, message) VALUES (:name,:contactNumber,:email, :message) ON DUPLICATE KEY UPDATE name='$solution'");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':contactNumber', $contactNumber);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            $stmt->execute();

            // Close the database connection
            $conn = null;

        } catch (PDOException $e) {
            // Handle database connection and query error
            echo "Error: " . $e->getMessage();
            exit;
        }

        // Send an email with the form data
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.gozoomtech.com'; // Replace with your SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'info@gozoomtech.com'; // Replace with your SMTP username
            $mail->Password = 'Gozoom@123'; // Replace with your SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, 'ssl' also possible
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('info@gozoomtech.com'); // Replace with the desired email address

            // Email content
            $mail->isHTML(false);
            $mail->Subject = 'Form Submission';
            $mail->Body = "Name: $name\n"
            . "Contact Number: $contactNumber\n"
                . "Email: $email\n"
                . "Message: $message\n";

            $mail->send();

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