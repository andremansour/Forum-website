<?php
   include('../private/header.php');
   include('../private/navbar.php');

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

   $ourPost = 0;
   if($info['uid'] == $_SESSION['loggedin'])
      $ourPost = 1;
?>

<div class="d-none" id="postID"><?= $id ?></div>

<div class="pageIndicator w-100 h-auto" page="formpost">
   <div class="container py-5">
      <div class="row">
         <div class="col-12 <?php if(isLoggedIn()) { ?>col-lg-9<?php } ?>">
            <h2><?= $info['title'] ?></h2>
            <p><abbr class="timeago" title="<?= $info['date'] ?>"></abbr></p>
            <hr>
            <div>
               <?= $info['body'] ?>
            </div>
         </div>
         <?php if((isLoggedIn() && $ourPost == 1) || isAdmin()) { ?>
         <div class="col-12 col-lg-3 text-center">
            <div class="d-none d-lg-block">
               <h2></h2><p class="lead">Thread Tools</p>
               <hr>
            </div>
            <a href="/editpost.php?id=<?= $id ?>" class="mdc-button mdc-button--raised bg-primary nounderline w-100 mt-4 mt-lg-0">
               <span class="mdc-button__ripple"></span><i class="fas fa-pencil-alt mr-2"></i> edit post
            </a>
            <div id="deletePost" class="mt-2 mdc-button mdc-button--raised bg-danger nounderline w-100">
               <span class="mdc-button__ripple"></span><i class="fas fa-trash mr-2"></i> delete post
            </div>
         </div>
         <?php } ?>
      </div>
      
   </div>
</div>