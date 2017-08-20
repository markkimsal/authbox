
<div class="success">
	<div class="row">
	<div class="col-xs-12">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Your Auth Code</h3>
			</div>
			<div class="panel-body">
				<input type="text" name="auth_code" id="auth_code" size="80">
			</div>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
		<div class="panel-body">
			<p>You can exchange this short-lived code in secret for an access_token.  The <code>authorization code</code> is only good for exchanging for an <code>access token</code>.</p>
		</div>
		</div>
	</div>
	</div>

	<div class="row">
	<div class="col-xs-12">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Exchange for an Access Token</h3>
			</div>
			<div class="panel-body">
			<p>Normally this exchange would happen between back-end servers and not from a browser.   The <code>authorization code</code> is usually delivered with a <code>refresh token</code> that can be used to extend the authorization.  If someone were to intercept this exchange, they could impersonate you indefinitely.</p>

			<form method="POST" action="{m_appurl ep='token/'}" id="form-exchange">

			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label class="control-label" for="grant_type">grant_type</label>
					<input class="form-control" type="text" name="grant_type" value="authorization_code">
				</div>

				<div class="form-group has-success">
					<label class="control-label" for="code">authorization_code</label>
					<input class="form-control" type="text" id="acode" name="code" value="">
				</div>

				<div class="form-group">
					<label class="control-label" for="client_id">client_id</label>
					<input class="form-control" type="text" name="client_id" value="testclient">
				</div>
				<div class="form-group">
					<label class="control-label" for="response_type">response_type</label>
					<input class="form-control" type="text" name="response_type" value="code id_token">
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label class="control-label" for="scope">scope</label>
					<input class="form-control" type="text" name="scope" value="profile email openid">
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

				<button id="btn-exchange" class="btn btn-primary">Exchange Code for Token</button>
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
				<h3>Exchange result</h3>
			</div>
			<div class="panel-body">
			<textarea rows="10" cols="50" id="ajax-result"></textarea>
			</div>
		</div>
	</div>
	</div>



</div>

<div class="row error" style="display:none;">
	<div class="col-xs-12">
		<div class="panel panel-danger">
		<div class="panel-heading">
			<h3>There was an error with your request :(</h3>
		</div>
		<div class="panel-body">
			<p id="request_error"></p>
		</div>
		</div>
	</div>
</div>


<script>

document.getElementById('btn-exchange').addEventListener("click", function(evt) {
evt.stopPropagation();
evt.preventDefault();
var request = new XMLHttpRequest();
var code =  document.getElementById('auth_code').value;

request.open('POST', '{m_appurl ep='token'}', true);
request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

request.onload = function() {
  if (request.status >= 200 && request.status < 400) {
    // Success!
	document.getElementById('ajax-result').innerText = request.responseText;
    var data = JSON.parse(request.responseText);
  } else {
    // We reached our target server, but it returned an error

  }
};

request.onerror = function() {
  // There was a connection error of some sort
};

var data = $('#form-exchange').serialize();
//request.send('response_type=code+id_token&client_id=testclient&state=abcd&scope=profile+openid&grant_type=authorization_code&code='+code+'&redirect_uri={m_appurl ep='test/code'}');
request.send(data);
});

var $payload = getHashVars();
if ($payload.length < 1) {
	$payload = getQueryVars();
}

if($payload.length == 0 ) {
$payload['error'] = 'no parameters';
$payload['error_description'] = 'No OAuth parameters were passed.';
}

if($payload.state != '1234' ) {
$payload['error'] = 'State doesn\'t match';
$payload['error_description'] = 'State parameter doesn\'t match what was sent, might be a hacking attempt.';
}


if ($payload['error']) {
	document.getElementById('request_error').innerText = decodeURIComponent($payload['error_description']);

	divList = document.getElementsByClassName('error');
	for( x=0; x < divList.length; x++) {
		console.log(divList[x]);
		divList[x].style.display = 'block';
	}
	divList = document.getElementsByClassName('success');
	for( x=0; x < divList.length; x++) {
		console.log(divList[x]);
		divList[x].style.display = 'none';
	}

} else {
	document.getElementById('auth_code').value = $payload['code'];
	document.getElementById('acode').value   = $payload['code'];
}

function getQueryVars() {
  var vars = [], hash;
  var hashes = window.location.search.slice(1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
      hash = hashes[i].split('=');
	  if (hash.length < 2) {
		  return [];
	  }
      vars.push(hash[0]);
      vars[hash[0]] = decodeURIComponent(hash[1].split('+').join('%20'));
  }
  return vars;
}

function getHashVars() {
  var vars = [], hash;
  var hashes = window.location.hash.slice(1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
      hash = hashes[i].split('=');
	  if (hash.length < 2) {
		  return [];
	  }

      vars.push(hash[0]);
      vars[hash[0]] = decodeURIComponent(hash[1].split('+').join('%20'));
  }
  return vars;
}
</script>
