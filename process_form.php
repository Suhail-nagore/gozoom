<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Send an email with the form data
    $to = "info@gozooomtech.com"; // Replace with the desired email address
    $subject = "Form Submission";
    $emailBody = "Name: $name\n";
    $emailBody .= "Designation: $designation\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Contact Number: $contactNumber\n";
    $emailBody .= "Industry: $industry\n";
    $emailBody .= "Company Name: $companyName\n";
    $emailBody .= "Solution: $solution\n";
    $emailBody .= "Message: $message\n";

    // Additional headers
    $headers = "From: $email" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";

    // Send the email
    mail($to, $subject, $emailBody, $headers);

    // Generate a response (e.g., success message, error message, redirection)
    //echo "<p>Form submitted successfully!</p>";
    header("Location: success.html");
    exit;
}
?>
