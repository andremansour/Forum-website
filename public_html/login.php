<?php
  include('../private/header.php');
?>
<div class="pageIndicator w-100 h-100" page="login">
  <div id="login" class="container h-100">
    <div class="d-flex align-items-center justify-content-center w-100 h-100">
      <div class="row">
        <div class="col-12 login-box">
          <a href="/" class="col-lg-12 mt-4 d-block login-key nounderline">
            <i class="fa fa-key" aria-hidden="true"></i>
          </a>
          <div class="col-lg-12 login-title">
            SIGN IN
          </div>
          <div class="col-lg-12 login-form">
            <div class="col-lg-12 login-form">
              <form id="loginform">
                <div class="form-group">
                  <label class="form-control-label">EMAIL</label>
                  <input id="usernamefield" type="text" class="form-control">
                </div>
                <div class="form-group">
                  <label class="form-control-label">PASSWORD</label>
                  <input id="passfield" type="password" class="form-control" i>
                </div>
                <div class="col-lg-12 loginbttm d-flex justify-content-center">
                  <div class="login-btm login-button">
                    <button type="submit" class="btn btn-outline-primary">SIGN IN</button>
                  </div>
                </div>
                <div class="alert alert-secondary text-center" role="alert">
                  Use the username "demo" and the password "demo" to access the demo account.
                </div>
                <div class="alert alert-danger text-center d-none" id="invaliduserpass" role="alert">
                  Invalid username/password combination.
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>