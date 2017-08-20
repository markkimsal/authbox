  <div class="register-box-body">
    <p class="login-box-msg">Add a password</p>

    <form action="{function="m_appurl('register/verify/password')"}?{$token}" method="post">
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password1">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Retype password" name="password2">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-sm-4">
        </div>
        <!-- /.col -->
        <div class="col-sm-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Set Password</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.register-box-body -->
