<?php
// Include the PHPMailer autoloader
require 'PHPMailer.php';
require 'Exception.php';
require 'SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $designation = $_POST["designation"];
    $email = $_POST["email"];
    $contactNumber = $_POST["contact-number"];
    $industry = $_POST["industry"];
    $companyName = $_POST["company-name"];
    $solution = $_POST["solution"];
    $message = $_POST["message"];

    // Validate and sanitize the form data (perform necessary checks)

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
        $stmt = $conn->prepare("INSERT INTO enquiry (name, designation, email, contact_number, industry, company_name, solution, message) VALUES (:name, :designation, :email, :contactNumber, :industry, :companyName, :solution, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':designation', $designation);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contactNumber', $contactNumber);
        $stmt->bindParam(':industry', $industry);
        $stmt->bindParam(':companyName', $companyName);
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

    // Send an email with the form data using PHPMailer
    $mail = new PHPMailer();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'mail.gozoomtech.com';  // Replace with your SMTP server address
    $mail->SMTPAuth = true;
    $mail->Username = 'info@gozoomtech.com';  // Replace with your SMTP username
    $mail->Password = 'Gozoom@123';  // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';  // Enable TLS encryption, 'ssl' also possible
    $mail->Port = 587;  // TCP port to connect to

    // Sender and recipient settings
    $mail->setFrom($email, $name);
    $mail->addAddress('info@gozoomtech.com');  // Replace with the desired email address

    // Email content
    $mail->Subject = 'Form Submission';
    $mail->Body = "Name: $name\n" .
        "Designation: $designation\n" .
        "Email: $email\n" .
        "Contact Number: $contactNumber\n" .
        "Industry: $industry\n" .
        "Company Name: $companyName\n" .
        "Solution: $solution\n" .
        "Message: $message\n";

    // Send the email
    if ($mail->send()) {
        // Redirect to success page
        header("Location: success.html");
        exit;
    } else {
        // Handle email sending error
        echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        exit;
    }
}
?>
