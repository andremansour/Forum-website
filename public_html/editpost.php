<?php
  include('../private/header.php');
  include('../private/navbar.php');

  // Make sure they're logged in
  if(!isLoggedIn()) {
    header('Location: /login.php');
    return;
  }

  if(!isset($_GET['id'])) {
    echo 'give me an ID';
    die();
  }

  $id = $_GET['id'];

  // Get post info
  $post = $con->prepare("SELECT * FROM posts WHERE id = :id");
  $post->bindParam(':id',$id);
  $post->execute();

  if($post->rowCount() == 0) {
    echo 'post not found';
    die();
  }

  $info = $post->fetch(PDO::FETCH_ASSOC);

  // Make sure it's their own post
  if($info['uid'] != $_SESSION['loggedin'] && !isAdmin()) {
    echo "You cannot edit others' posts!";
    die();
  }
?>

<div class="d-none" id="body"><?= $info['body'] ?></div>
<div class="d-none" id="postID"><?= $id ?></div>

<div class="pageIndicator w-100 h-auto" page="editpost">
  <div class="container py-5">
    <h1 class="mb-0">Edit Post</h1>
    <p class="lead mt-2">Make changes to your original post!</p>
    <div class="p-4 bg-light rounded">
      <input class="py-4 mt-0" type="text" id="title" maxlength="100" placeholder="Title" value="<?= $info['title'] ?>">
      <div class="options">
        <button onClick="transform('bold', null)">
        <i class="fas fa-bold"></i>
        </button>
        <button onClick="transform('italic', null)">
        <i class="fas fa-italic"></i>
        </button>
        <button onClick="transform('strikeThrough', null)">
        <i class="fas fa-strikethrough"></i>
        </button>
        <button onClick="transform('underline', null)">
        <i class="fas fa-underline"></i>
        </button>
        <div class="seperator"></div>
        <button onClick="transform('justifyLeft', null)">
        <i class="fas fa-align-left"></i>
        </button>
        <button onClick="transform('justifyCenter', null)">
        <i class="fas fa-align-center"></i>
        </button>
        <button onClick="transform('justifyRight', null)">
        <i class="fas fa-align-right"></i>
        </button>
        <div class="seperator"></div>
        <button onClick="transform('insertOrderedList', null)">
        <i class="fas fa-list-ol"></i>
        </button>
        <button onClick="transform('insertUnorderedList', null)">
        <i class="fas fa-list-ul"></i>
        </button>
        <div class="seperator"></div>
        <select onChange="transform('fontName', this.value)">
          <option disabled default>Font</option>
          <option value="Times New Roman">Times</option>
          <option value="Arial">Arial</option>
          <option value="Courier New">Courier New</option>
          <option value="Tahoma">Tahoma</option>
          <option value="Arial">Verdana</option>
        </select>
        <div class="seperator"></div>
        <input type="number" onChange="transform('fontSize', this.value)" value="3" max="7" min="1"></input>
      </div>
      <iframe class="mt-2" name="editor" id="editor"></iframe>
      <p class="mb-0 mt-1 text-right text-dark"><span id="chars">0</span></p>
    </div>
    <button id="submit" class="mdc-button mdc-button--raised bg-success nounderline mt-3 w-100">
      <span class="mdc-button__ripple"></span><i class="fas fa-pencil-alt mr-2"></i> edit your post
    </button>
  </div>
</div>

</body>
</html>