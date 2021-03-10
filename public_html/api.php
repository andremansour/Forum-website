<?php

session_start();

include('../private/database.php');

if(isset($_POST['type'])) {
	switch ($_POST['type']) {
		case 'loadposts':
			$curpage = $_POST['curpage'];

			$postscount = $con->prepare("select null from posts");
			$postscount->execute();
			$postscount = $postscount->rowCount();

			$numperpage = 20;
			$numpages = ceil($postscount/$numperpage);

			$minLimit = $curpage*$numperpage-$numperpage;

			$posts = $con->prepare("select * from posts limit ".$minLimit.",".$numperpage);
			$posts->execute();

			$data = array(
				'success' => 1,
				'data' => $posts->fetchAll(),
				'numpages' => $numpages,
			);

			echo json_encode($data);

			break;
		case 'login':
			$validUser = "demo";
			$validPass = "demo";

			$user = $_POST['user'];
			$pass = $_POST['pass'];

			$dbcheck = $con->prepare("SELECT id,pass FROM users WHERE email = :email");
			$dbcheck->bindParam(":email",$user);
			$dbcheck->execute();

			$good = 0;
			$id = 0;

			if($dbcheck->rowCount() > 0) {
				$info = $dbcheck->fetch();
				$the_id = $info['id'];
				$hash = $info['pass'];

				if(password_verify($pass,$hash)) {
					$good = 1;
					$id = $the_id;
				}
			}

			if($good == 1) {
				$data = array(
					'success' => 1,
					'valid' => 1,
				);

				$_SESSION['loggedin'] = $id;

				echo json_encode($data);
			} else {
				$data = array(
					'success' => 1,
					'valid' => 0,
				);

				echo json_encode($data);
			}

			break;
		case 'logout':
			session_destroy();

			echo json_encode(array('success' => 1));

			break;
		case 'post':
			// Make sure they're logged in
			if(!isLoggedIn()) {
				echo json_encode(array('success' => 0));
				return;
			}

			$title = $_POST['title'];
			$body = $_POST['body'];

			$bodyLen = strlen(strip_tags($body));

			if(strlen($title) < 1 || strlen($title) > 100) {
				echo json_encode(array('success' => 0,'msg' => "Give your post a title!"));
				return;
			}

			if($bodyLen > 2000) {
				echo json_encode(array('success' => 0,'msg' => "Your post can't be longer than 2,000 characters!"));
				return;
			} else if($bodyLen < 1) {
				echo json_encode(array('success' => 0,'msg' => "Please write your post!"));
				return;
			}

			// Security: Replace all "<script>" tags so that people don't do stupid things
			$body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $body);

			$insert = $con->prepare("INSERT INTO posts (title,body,date,uid) VALUES (:title,:body,NOW(),:uid)");
			$insert->bindParam(':title',$title);
			$insert->bindParam(':body',$body);
			$insert->bindParam(':uid',$_SESSION['loggedin']);
			$insert->execute();

			if($insert->rowCount())
				echo json_encode(array('success' => 1,'postID' => $con->lastInsertId()));
			else
				echo json_encode(array('success' => 0));

			break;
		case 'edit':
			// Make sure they're logged in
			if(!isLoggedIn()) {
				echo json_encode(array('success' => 0));
				return;
			}

			$title = $_POST['title'];
			$body = $_POST['body'];
			$id = $_POST['id'];

			$bodyLen = strlen(strip_tags($body));

			if(strlen($title) < 1 || strlen($title) > 100) {
				echo json_encode(array('success' => 0,'msg' => "Your post must have a title!"));
				return;
			}

			if($bodyLen > 2000) {
				echo json_encode(array('success' => 0,'msg' => "Your post can't be longer than 2,000 characters!"));
				return;
			} else if($bodyLen < 1) {
				echo json_encode(array('success' => 0,'msg' => "Please write your post!"));
				return;
			}

			// Security: Replace all "<script>" tags so that people don't do stupid things
			$body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $body);

			if(!isAdmin()) {
				$update = $con->prepare("UPDATE posts SET title = :title, body = :body WHERE id = :id AND uid = :uid");
				$update->bindParam(':title',$title);
				$update->bindParam(':body',$body);
				$update->bindParam(':id',$id);
				$update->bindParam(':uid',$_SESSION['loggedin']);
				$update->execute();
			} else {
				$update = $con->prepare("UPDATE posts SET title = :title, body = :body WHERE id = :id");
				$update->bindParam(':title',$title);
				$update->bindParam(':body',$body);
				$update->bindParam(':id',$id);
				$update->execute();
			}

			echo json_encode(array('success' => 1));

			break;
		case 'delete':
			// Make sure they're logged in
			if(!isLoggedIn()) {
				echo json_encode(array('success' => 0));
				return;
			}

			$id = $_POST['id'];

			if(!isAdmin()) {
				$delete = $con->prepare("DELETE FROM posts WHERE id = :id AND uid = :uid");
				$delete->bindParam(':id',$id);
				$delete->bindParam(':uid',$_SESSION['loggedin']);
				$delete->execute();
			} else {
				$delete = $con->prepare("DELETE FROM posts WHERE id = :id");
				$delete->bindParam(':id',$id);
				$delete->execute();
			}

			if($delete->rowCount() > 0)
				echo json_encode(array('success' => 1));
			else
				echo json_encode(array('success' => 0));

			break;
		case 'welcome':
			// Make sure they're logged in
			if(!isLoggedIn()) {
				echo json_encode(array('success' => 0));
				return;
			}

			$new = $_POST['new'];

			$setWelcomer = $con->prepare('UPDATE users SET welcomer = :welcomer WHERE id = :uid');
			$setWelcomer->bindParam(':welcomer',$new);
			$setWelcomer->bindParam(':uid',$_SESSION['loggedin']);
			$setWelcomer->execute();

			if($setWelcomer->rowCount() == 0) {
				echo json_encode(array('success' => 0));
			} else {
				echo json_encode(array('success' => 1));
			}

			break;
		case 'contactform':
			$name = $_POST['name'];
			$body = $_POST['body'];

			$sendMsg = $con->prepare("INSERT INTO inquiries (name,body,date) VALUES(:name,:body,NOW())");
			$sendMsg->bindParam(':name',$name);
			$sendMsg->bindParam(':body',$body);
			$sendMsg->execute();

			if($sendMsg->rowCount() == 0) {
				echo json_encode(array('success' => 0));
			} else {
				echo json_encode(array('success' => 1));
			}

			break;
	}
} else {
	echo json_encode(array('success' => 2));
}

?>