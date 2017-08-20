

  <div class="register-box-body">
    <p class="login-box-msg">Enter your email address and we'll send a link to reset your password.</p>

    <form action="{{#appUrl}}register/password/resend{{/appUrl}}" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email" value="{{email}}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-6">
		&nbsp;
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="{{#appUrl}}login{{/appUrl}}" class="text-center">I already have a membership</a>
  </div>
  <!-- /.register-box-body -->
