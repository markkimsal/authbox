
	<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			<h2>3 Legged Workflow (Authorization Code Response Type)</h2>
		</div>
		<div class="panel-body">

			<form method="GET" action="{m_appurl ep='authorize/'}">

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label class="control-label" for="client_id">client_id</label>
					<input class="form-control" type="text" name="client_id" value="testclient">
				</div>
				<div class="form-group">
					<label class="control-label" for="response_type">response_type</label>
					<input class="form-control" type="text" name="response_type" value="code">
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label class="control-label" for="scope">scope</label>
					<input class="form-control" type="text" name="scope" value="openid profile email">
				</div>

				<div class="form-group">
					<label class="control-label" for="state">state</label>
					<input class="form-control" type="text" name="state" value="1234">
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label" for="redirect_uri">redirect_uri</label>
					<input class="form-control" type="text" name="redirect_uri" value="{m_appurl ep='test/code'}">
				</div>

				<button class="btn btn-primary">3 Legged Workflow (Authorization Code Response Type)</button>
			</div>
			</form>
		</div>
		</div>
	</div>
	</div>

	<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Implicit Workflow (Token Response Type)</h2>
		</div>

		<div class="panel-body">

			<form method="GET" action="{m_appurl ep='authorize/'}">

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label class="control-label" for="client_id">client_id</label>
					<input class="form-control" type="text" name="client_id" value="testclient">
				</div>
				<div class="form-group">
					<label class="control-label" for="response_type">response_type</label>
					<input class="form-control" type="text" name="response_type" value="token">
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label class="control-label" for="scope">scope</label>
					<input class="form-control" type="text" name="scope" value="openid profile email">
				</div>

				<div class="form-group">
					<label class="control-label" for="state">state</label>
					<input class="form-control" type="text" name="state" value="1234">
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label" for="redirect_uri">redirect_uri</label>
					<input class="form-control" type="text" type="hidden" name="redirect_uri" value="{m_appurl ep='test/implicit'}">
				</div>

				<button type="submit" class="btn btn-default">Implicit Workflow (Token Response Type)</button>
			</div>

			</form>
		</div>
		</div>
	</div>
	</div>

