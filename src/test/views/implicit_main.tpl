
<div class="success">
	<div class="row">
	<div class="col-xs-12">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Your access token</h3>
			</div>
			<div class="panel-body">
				<input type="text" name="access_token" id="access_token" size="80">
			</div>
		</div>
	</div>
	</div>
	<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
		<div class="panel-body">
			<p>You can use this token to access arbitrary APIs with HTTP Header.  The APIs are outside the scope of OAuth, but they must be built to understand OAuth tokens.</p>
			<code>Authorization: Bearer <span id="atoken"></span></code></p>
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
var $payload = getHashVars();
if (!$payload) {
	$payload = getQueryVars();
}
if($payload.length == 0 ) {
$payload['error'] = 'no parameters';
$payload['error_description'] = 'No OAuth parameters were passed.';
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
	document.getElementById('access_token').value = $payload['access_token'];
	document.getElementById('atoken').innerText   = $payload['access_token'];
}

function getQueryVars() {
  var vars = [], hash;
  var hashes = window.location.query.slice(1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
      hash = hashes[i].split('=');
	  if (hash.length < 2) {
		  return [];
	  }
      vars.push(hash[0]);
      vars[hash[0]] = decodeURIComponent(hash[1].replace('+').join('%20'));
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
