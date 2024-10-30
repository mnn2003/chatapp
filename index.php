<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: users.php");
  }
?>

<?php include_once "header.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
         #image-upload {
            display: none;
        }
		
.profile-info img {
    height: 120px;
    width: 120px;
    border: 5px dotted #ccc;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(10, 0, 0, 0.5);
    transition: transform 0.3s ease-in-out;
    animation: moveBorder 5s linear infinite; 
}

@keyframes moveBorder {
    0% {
        border-radius: 50%;
    }
    25% {
        border-radius: 0%;
    }
    50% {
        border-radius: 50%;
    }
    75% {
        border-radius: 100%;
    }
    100% {
        border-radius: 50%;
    }
}


.profile-info img:hover {
    transform: scale(1.1);
}

.profile-info img.grayscale {
    filter: grayscale(100%);
}

.profile-info img.sepia {
    filter: sepia(50%);
}
  </style>
</head>
<body>
  <div class="wrapper">
    <section class="form signup">
	<center><header>Slient Circle</header></center>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
		<div class="col-md-12 form-group">
			<center>
            <div class="profile-info">
				<img src="php/images/defaultpreview.png" alt="Profile Image" id="profile-image" onclick="changeImage()">
				<input type="file" name="image" id="image-upload" accept="php/image/x-png,image/gif,image/jpeg,image/jpg">
					<h5>Profile Picture:</h5>
			</div>
			</center>
       </div>
        <div class="name-details">
          <div class="field input">
            <label>First Name</label>
            <input type="text" name="fname" placeholder="First name" required>
          </div>
          <div class="field input">
            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Last name" required>
          </div>
        </div>
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter new password" required>
          <i class="fas fa-eye"></i>
        </div>
        
		
        <div class="field button">
          <input type="submit" name="submit" value="Continue to Chat">
        </div>
      </form>
      <div class="link">Already signed up? <a href="login.php">Login now</a></div>
    </section>
  </div>

  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>
  <script> 
  
    // JavaScript function to change the profile image
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
