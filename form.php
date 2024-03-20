<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "C:/xampp/htdocs/website/PHPMailer-master/src/PHPMailer.php";
require "C:/xampp/htdocs/website/PHPMailer-master/src/SMTP.php";
require "C:/xampp/htdocs/website/PHPMailer-master/src/Exception.php";
require "C:/xampp/htdocs/website/config/config.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP configuration
        $mail->SMTPDebug  = 0; // Set debug to 0 to not show any debug lines
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host       = $config["email"]["host"];
        $mail->Username   = $config["email"]["username"];
        $mail->Password   = $config["email"]["password"];
        $mail->Port       = $config["email"]["port"];
        $mail->SMTPSecure = $config["email"]["secure"];
        
        // Set sender and recipient
        $mail->setFrom("max@codestaff.info", "Webform");
        $mail->addAddress("max@codestaff.info");
        
        // Sanitize and retrieve form data
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
        $text = filter_var($_POST["text"], FILTER_SANITIZE_STRING);
        
        // Set email content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $text";
        
        // Validate name
        $nameChecked = false;
        if (!ctype_alpha($name)) {
            echo "<script type='text/javascript'>alert('Only use alphabetic letters for Name');</script>";
        } else {
            $nameChecked = true; // Set to true if name consists only of letters
        }
        
        // If name and email are valid, send the email
        if ($nameChecked && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail->send();
            echo "<script type='text/javascript'>alert('Message sent');</script>";
        }
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Contact</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input class="Filltext" type="text" name="name" placeholder="Name" required><br>
        <input class="Filltext" type="email" name="email" placeholder="Email" required><br>
        <input class="Filltext" type="text" name="subject" placeholder="Subject" required><br>
        <input class="Filltext" class="Formtext" type="text" name="text" placeholder="Text" required>
        <button class="Sendbutton" type="submit">Send</button>
    </form>
</body>
</html>
