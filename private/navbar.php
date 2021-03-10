<nav class="navbar navbar-expand-lg navbar-light border-bottom">
    <div class="container">
    	<a class="navbar-brand" href="/"><img width="90" src="/assets/img/lion.svg"><span class="font-weight-bold ml-1">EPIC</span>FORUMS</a>
    	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    		<span class="navbar-toggler-icon"></span>
    	</button>
    	<div class="collapse navbar-collapse" id="navbarNavDropdown">
    		<ul class="navbar-nav mr-auto">
            </ul>
    		<ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/contactus.php"><i class="fas fa-envelope fa-fw"></i> Contact Us</a>
                </li>
                <?php
                if(isLoggedIn()) {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i> Demo Account
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" id="logout"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</a>
                        <?php
                        if(isAdmin()) {
                        ?>
                        <a class="dropdown-item" href="/admin.php"><i class="fas fa-gavel fa-fw"></i> Admin</a>
                        <?php
                        }
                        ?>
                    </div>
                </li>
                <?php
                } else {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Sign In <i class="fas fa-sign-in-alt fa-fw"></i></a>
                </li>
                <?php
                }
                ?>
    		</ul>
    	</div>
    </div>
</nav>