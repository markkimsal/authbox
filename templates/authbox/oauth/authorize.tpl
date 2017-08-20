<div>
<h4><strong>{$client->client_name}</strong> is requesting the following permission:</h4>
<dl class="scopes">
{foreach $scopeList as $scope}
{assign var=incfile value="ifexists:templates/authbox/oauth/scope_dt_"|cat:$scope|cat:".tpl" nocache }
{include file=$incfile}
{/foreach}
</dl>
</div>

<form method="POST" action="{"authorize/main/verify"|appurl}">
	<br/>
	<button class="btn btn-primary" type="submit" name="auth" value="Yes">Allow</button>
	<button class="btn btn-default" type="submit" name="cancel" value="No">Don't Allow</button>

	<input type="hidden" value="{$response_type}" name="response_type">
	<input type="hidden" value="testclient" name="client_id">
	<input type="hidden" value="{$redirect_uri}" name="redirect_uri">
	<input type="hidden" value="{$state}" name="state">
	<input type="hidden" value="{$scope}" name="scope">
</form>
