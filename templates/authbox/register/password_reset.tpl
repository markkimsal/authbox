
  <div class="register-box-body">
    <p class="login-box-msg">Enter your password to update it.</p>

    <form action="{{#appUrl}}register/password/reset{{/appUrl}}?{{token}}" method="post">
      <div class="form-group">
        <input type="password" class="form-control" placeholder="password" name="password">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" placeholder="retype" name="password2">
      </div>
      <div class="row">
        <div class="col-xs-6">
		&nbsp;
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
          <input  type="hidden" value="submit" name="submit">
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="{{#appUrl}}login{{/appUrl}}" class="text-center">I already have a membership</a>
  </div>
  <!-- /.register-box-body -->
