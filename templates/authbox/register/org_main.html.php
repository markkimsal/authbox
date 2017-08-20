  <div class="register-box-body">
    <p class="login-box-msg">Setup your company</p>
	<?php echo Metrofw_Template::parseSection('sparkmsg'); ?>

    <form action="<?php echo m_appurl('register/org/save');?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Company Name" name="org_name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Company Address" name="org_address">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
      </div>
      <div class="form-group has-feedback">
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="checkbox icheck">
            <label>
            </label>
          </div>
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

