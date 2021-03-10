<?php
	include('../private/header.php');
	include('../private/navbar.php');

	// Check welcomer
	$welcomer = 0;
	if(isLoggedIn()) {
		$checkWelcome = $con->prepare("SELECT welcomer FROM users WHERE id = :uid");
		$checkWelcome->bindParam(':uid',$_SESSION['loggedin']);
		$checkWelcome->execute();
		$welcomer = $checkWelcome->fetch()['welcomer'];
	}

	// Welcome them, if necessary
	if($welcomer == 1) {
		echo <<<HTML
		<script type="text/javascript">swal("Welcome!","Thanks for visiting our site!","success")</script>
HTML;
	}
?>

<div id="templateCard">
	<a href="/formpost.php?id=the_ID" class="mdc-card demo-card demo-card-shaped {$marg} d-none text-dark nounderline">
		<div class="mdc-card__primary-action demo-card__primary-action mdc-ripple-upgraded p-3" tabindex="0" style="--mdc-ripple-fg-size:210px; --mdc-ripple-fg-scale:1.83226; --mdc-ripple-fg-translate-start:-86px, -75.0625px; --mdc-ripple-fg-translate-end:70px, -38px;">
			<div class="demo-card__primary">
				<h2 class="demo-card__title mdc-typography mdc-typography--headline6 overflowHider">{$value["title"]}</h2>
				<h3 class="demo-card__subtitle mdc-typography mdc-typography--subtitle2"><abbr class="timeago" title="A few moments ago"></abbr></h3>
			</div>
			<div class="demo-card__secondary mdc-typography mdc-typography--body2 overflowHider">{$value["body"]}</div>
		</div>
	</a>
</div>

<div id="postsList" class="container py-5 pageIndicator h-auto" page="home">
	<div class="d-flex <?php if(isLoggedIn()) { ?>justify-content-between<?php } else { echo("justify-content-end"); } ?> mb-4 text-white">
		<?php if(isLoggedIn()) { ?>
		<div class="mdc-form-field">
			<div id="switchActual" class="mdc-switch">
				<div class="mdc-switch__track"></div>
				<div class="mdc-switch__thumb-underlay mdc-ripple-upgraded mdc-ripple-upgraded--unbounded" style="--mdc-ripple-fg-size:28px; --mdc-ripple-fg-scale:1.71429; --mdc-ripple-left:10px; --mdc-ripple-top:10px;">
					<div class="mdc-switch__thumb">
						<input id="welcomer" class="mdc-switch__native-control" <?php if($welcomer == 1) { ?>checked="checked"<?php } ?> type="checkbox" role="switch" id="switch-item1">
					</div>
				</div>
			</div>
			<label class="ml-2" for="switch-item1">Welcomer</label>
		</div>
		<?php } ?>
		<a href="/post.php" class="mdc-button mdc-button--raised bg-success nounderline">
			<span class="mdc-button__ripple"></span><i class="fas fa-plus mr-2"></i> new thread
		</a>
	</div>
	<div id="load_less">&nbsp;</div>
	<div id="posts">
		<div id="postStartSpinner" class="d-flex align-items-center justify-content-center">
			<div class="spinner-border" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
	</div>
	<div id="postAfterSpinner" class="align-items-center justify-content-center mt-5 d-none">
		<div class="spinner-border" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
	<div id="load_more">&nbsp;</div>
</div>
</body>
</html>