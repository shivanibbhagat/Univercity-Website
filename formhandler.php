<?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $subject = $_POST['subject'];

    if (!empty($name) || !empty($email)) {
        $host = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $dbname = "login";

        //Create connection
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
        
        //Send an email
        $recipient = "atharvakapile1156@gmail.com";
        $subject = "New subscription form $name";
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n";

        $email_headers = "From: $name <$email>";

        if (mysqli_connect_error()) {
            die('Connect Error('.mysqli_connect_error().')'.mysqli_connect_error());
        } else {
            $SELECT = "SELECT email From feedback Where email = ? Limit 1";
            $INSERT = "INSERT Into feedback (name, email, subject, message) values(?,?,?,?)";

            //Prepare statement
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($email);
            $stmt->store_result();
            $rnum = $stmt->num_rows();

            if ($rnum == 0) {
                $stmt->close();

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("ssss", $name, $email, $subject, $message);
                $stmt->execute();
                header("Location: http://localhost/login/contact.html?success=1#form");
            } else {
                header("Location: http://localhost/login/contact.html?success=-1#form");
                exit;
            }
            $stmt->close();
            $conn->close();
        }
        mail($recipient, $subject, $email_content, $email_headers);
    }
?>