<?php
  include('../private/header.php');
  include('../private/navbar.php');

  if(!isAdmin()) {
  	header('Location: /');
  }
?>

<div class="container py-5 pageIndicator h-auto" page="admin">
	<h1 class="display-4 text-center">Admin Dashboard</h1>
	<p class="text-center mb-4">Read all of the inquiries here.</p>

	<div id="inquiries">
		<?php
		$inquiries = $con->prepare("SELECT * FROM inquiries");
		$inquiries->execute();
		$inquiries = $inquiries->fetchAll();

		foreach ($inquiries as $key => $value) {
			$name = $value['name'];
			$body = $value['body'];
			echo <<<HTML
			<div class="bg-light rounded mb-3 p-3">
				<div>$name</div>
				<div>$body</div>
			</div>
HTML;
		}
		?>
	</div>
</div>