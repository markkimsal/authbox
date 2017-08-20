  <div class="register-box-body">
    <p class="login-box-msg">Setup your company</p>

    <form action="{function="m_appurl('register/org/save')"}" method="post">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Company Name" name="org_name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-sm-4">
        </div>
        <!-- /.col -->
        <div class="col-sm-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Create Company</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.register-box-body -->
