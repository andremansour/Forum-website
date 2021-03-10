<?php
  include('../private/header.php');
  include('../private/navbar.php');

  // Make sure they're logged in
  if(!isLoggedIn()) {
    header('Location: /login.php');
    return;
  }
?>

<div class="pageIndicator w-100 h-auto" page="post">
  <div class="container py-5">
    <h1 class="mb-0">New Post</h1>
    <p class="lead mt-2">Join the conversation now!</p>
    <div class="p-4 bg-light rounded">
      <input class="py-4 mt-0" type="text" id="title" maxlength="100" placeholder="Title">
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
      <span class="mdc-button__ripple"></span><i class="fas fa-plus mr-2"></i> finish & post
    </button>
  </div>
</div>

</body>
</html>