<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, application/json");
header("Access-Control-Allow-Credentials: true");


$conn = new mysqli("sql.freedb.tech", "freedb_ledger_user", "6n&tyApRQ2Km6JC", "freedb_open_ledger");

// Check database connection 
if($conn -> connect_errno) {
    echo json_encode(array("message" => "databaseError"));
    die();
}



$userEmail = isset($_POST['user_email'])?$_POST['user_email']:null;
$userPass = isset($_POST['user_pass'])?$_POST['user_pass']:null;
$treasuryID = isset($_POST['treasury_ID'])?$_POST['treasury_ID']: null;


// echo $userEmail;

function fetchTreasuryDetails($conn, $treasuryID) {
    $stmt = $conn->prepare("SELECT treasury_name, link FROM treasury JOIN image_ref ON treasury.cover_img = image_ref.image_id WHERE treasury_ID = ?");
    $stmt->bind_param("s", $treasuryID);

    $stmt->execute();
    $stmt->bind_result($treasuryName, $link);

    $treasuryDetails = array();
    if($stmt->fetch()) {
        $treasuryDetails['treasuryID'] = $treasuryID;
        $treasuryDetails['treasuryName'] = $treasuryName;
        $treasuryDetails['link'] = $link;
        
        session_start();
        $_SESSION['treasury_data'] = json_encode($treasuryDetails, JSON_PRETTY_PRINT);
        header("Location: front.php");
        exit();
    }     
   
}


function addParticipant($conn, $userEmail, $userPass, $treasuryID) {
    
    $stmt = $conn->prepare("SELECT user_ID, password_hash FROM user WHERE user_email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->bind_result($userID, $password_hash);

    if($stmt->fetch()) {
        $stmt->close();
        // Results are present
        if(password_verify($userPass, $password_hash)) {
            // Password is correct 
            echo "Password match<br>";
            // Test whether user already exist in the treasury
            $treasuryCheck = $conn->prepare("SELECT role from treasury_participants WHERE user_ID = ? AND treasury_ID = ?");
            $treasuryCheck->bind_param("ss", $userID, $treasuryID);
            $treasuryCheck->execute();

            $treasuryCheck->bind_result($role);
            if($treasuryCheck->num_rows > 0) {
                // User already member of the treasury
                echo "ALready a member<br>";
            } else {
                $treasuryCheck->close();
                // All clear to add to the treasury group
                $addUser = $conn->prepare("INSERT INTO treasury_participants (treasury_ID, user_ID, role) VALUES('$treasuryID', '$userID', 'Member')");
 
                $addUser->execute();

                echo "No problem";
                // Head to successfully created
                header('Location: success.html');
                
            }

        } else {
            // Incorrect password
            echo "Incorrect password";
            header('Location: front.php?error=true');
        }
    } else {
        // Invalid user 
        echo "Invalid user";
        header('Location: front.php?error=true');
    }
}





if(isset($_GET['treasury'])) {
    fetchTreasuryDetails($conn, base64_decode($_GET['treasury']));
} else {
    


        addParticipant($conn, $userEmail, $userPass, $treasuryID);
        
}






