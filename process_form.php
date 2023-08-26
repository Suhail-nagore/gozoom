<?php
require 'vendor/autoload.php';
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';


use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Twilio credentials
$accountSid = 'AC875e57fee731961c13eee99327b35348';
$authToken = '69fb4f61fc98c2fce63d2c532ff42626';
$twilioNumber = '+14155238886'; // Your Twilio phone number

$recaptcha_secret = "6Lf9TsQnAAAAAAyZiEcU4rEDn7fVGOGKr7kISjBn"; // Replace with your secret key
$recaptcha_response = $_POST['g-recaptcha-response'];

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) !== 1) {
    echo "reCAPTCHA verification failed.";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $contactNumber = $_POST["contact-number"];
        $solution = $_POST["solution"];
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
            $stmt = $conn->prepare("INSERT INTO enquiry (name, email, contact_number, solution, message) VALUES (:name, :email, :contactNumber, :solution, :message) ON DUPLICATE KEY UPDATE name='$solution'");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contactNumber', $contactNumber);
            $stmt->bindParam(':solution', $solution);
            $stmt->bindParam(':message', $message);
            $stmt->execute();

            // Close the database connection
            $conn = null;


            // Construct the WhatsApp message for the customer
           $customerMessage = "Hello $name! Thank you for submitting the form. We will contact you at $email.";

           // Initialize the Twilio client
            $twilio = new Client($accountSid, $authToken);

          // Send WhatsApp message to the customer
          $twilio->messages->create(
          "whatsapp:$contactNumber", // Customer's WhatsApp phone number
        array(
                "from" => "whatsapp:$twilioNumber", // Use your Twilio phone number as the sender
                "body" => $customerMessage
            )
        );


            // Construct the WhatsApp message for the owner
            $ownerMessage = "New form submission from $name ($email). Please check the dashboard.";

            // Send WhatsApp message to the owner
            $twilio->messages->create(
                  "whatsapp:+919456222022", // Owner's WhatsApp phone number
         array(
             "from" => "whatsapp:$twilioNumber", // Use your Twilio phone number as the sender
             "body" => $ownerMessage
            )
        );

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
                . "Email: $email\n"
                . "Contact Number: $contactNumber\n"
                . "Solution: $solution\n"
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
