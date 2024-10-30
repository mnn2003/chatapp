<?php 
session_start();
include_once "php/config.php";
if(!isset($_SESSION['unique_id'])){
  header("location: login.php");
}
?>
<?php include_once "header.php"; ?>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
            header("location: users.php");
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo $row['img']; ?>" alt="">
        <div class="details">
          <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
        </div>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area" id="imageForm" enctype="multipart/form-data">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <textarea type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off"></textarea>
        <label for="file">
          <i style="font-size: 34px; padding: 5px" class="fa">&#xf030;</i>
        </label>
        <input name="file" type="file" id="file" style="display:none;" accept="image/*" onchange="updateFileName(this)" />
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>

    </section>
  </div>

  <script>
const form = document.querySelector(".typing-area"),
  incoming_id = form.querySelector(".incoming_id").value,
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button"),
  chatBox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
  e.preventDefault();
};

inputField.focus();

inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

sendBtn.onclick = () => {
  if (inputField.value.trim() !== "" || form.querySelector('[name="file"]').files.length > 0) {
    sendMessage();
  }
};

function sendMessage() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-ppmss.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        inputField.value = "";
        form.querySelector('[name="file"]').value = ""; // Clear the file input
        scrollToBottom();
      }
    }
  };

  let formData = new FormData(form);

  // Move this block inside the sendMessage function
  if (formData.get("file")) {
    // If it's a file, append the incoming_id and send the image
    formData.append("incoming_id", incoming_id);
    xhr.send(formData);
  } else {
    // If it's not a file, send it as a text message
    let textData = "incoming_id=" + incoming_id + "&message=" + inputField.value;
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(textData);
  }
}

chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

setInterval(() => {
  getMessages();
}, 500);

function getMessages() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-ppmss.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        chatBox.innerHTML = data;
        if (!chatBox.classList.contains("active")) {
          scrollToBottom();
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id=" + incoming_id);
}

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

  </script>

</body>
</html>
