<?php
session_start();
include_once "php/config.php";

if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

$unique_id = $_SESSION['unique_id'];
$sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = $unique_id");
if ($sql) {
    $row = mysqli_fetch_assoc($sql);

    $fname = $row['fname'];
    $lname = $row['lname'];
    $email = $row['email'];
    $img = $row['img'];
    $password = $row['password'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $new_lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    $new_image = ''; 
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $target_dir = "php/images/";
        $target_file = $target_dir . basename($_FILES['img']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $allowed_extensions)) {
            if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                $new_image = basename($_FILES['img']['name']);
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
            exit();
        }
    } else {
        $new_image = $row['img'];
    }

    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $new_password = md5($new_password);
        } else {
            echo '<script>alert("Passwords do not match!");</script>';
            exit();
        }
    } else {
        $new_password = $row['password'];
    }

    $updateQuery = "UPDATE users SET fname = '$new_fname', lname = '$new_lname', email = '$new_email', password = '$new_password', img = '$new_image' WHERE unique_id = $unique_id";

    if (mysqli_query($conn, $updateQuery)) {
        echo '<script>alert("Profile updated!"); window.location.href = "users.php";</script>';
        exit();
    } else {
        echo "Error updating user data: " . mysqli_error($conn);
    }
}
?>

<?php include_once "header.php"; ?>

<body>
    <div class="wrapper">    
        <section class="form signup">
            <header><center>Update Profile</center></header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="col-md-12 form-group">
                    <center>
                        <div class="profile-info">
                            <img src="php/images/<?php echo $row['img']; ?>" alt="Profile Image" id="profile-image"
                                onclick="changeImage()">
                            <input type="file" name="img" id="image-upload" accept="image/x-png,image/gif,image/jpeg,image/jpg">
                            <h5>Profile Picture:</h5>
                        </div>
                    </center>
                </div>
                <div class="name-details">
                    <div class="field input">
                        <label>First Name</label>
                        <input type="text" name="fname" placeholder="First name" value="<?php echo $fname; ?>">
                    </div>
                    <div class="field input">
                        <label>Last Name</label>
                        <input type="text" name="lname" placeholder="Last name" value="<?php echo $lname; ?>">
                    </div>
                </div>
                <div class="field input">
                    <label>Email Address</label>
                    <input type="text" name="email" placeholder="Enter your email" value="<?php echo $email; ?>">
                </div>
                <div class="field input">
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Enter new password">
                    <i class="fas fa-eye password-toggle"></i>
                </div>
                <div class="field input">
                    <label>Re-Enter New Password</label>
                    <input type="password" name="confirm_password" placeholder="Re-enter new password">
                    <i class="fas fa-eye confirm-password-toggle"></i>
                </div>

                <div class="field button">
                    <input type="submit" name="submit" value="Update">
                </div>
            </form>
        </section>
    </div>
    <script src="javascript/pass-show-hide.js"></script>
    <script>
        function changeImage() {
            const imageUpload = document.getElementById("image-upload");
            const profileImage = document.getElementById("profile-image");

            imageUpload.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            imageUpload.click();
        }
    </script>
</body>
</html>
