<div class="register-box-body">
<h2>Check Your Email</h2>
<p>
A validation email has been sent to: <em>{$email}</em>.</p>
<p>Check your email for instructions
on finalizing the sign-up process.
</p>


<br/>
{if="$emaildomain == 'gmail.com'"}
<p class="well">
<a target="_blank"  href="https://gmail.com/">Jump to Gmail in a new window.</a>
</p>
{/if}

{if="$emaildomain == 'yahoo.com'"}
<p class="well">
<a target="_blank"  href="https://mail.yahoo.com/">Jump to Yahoo Mail in a new window.</a>
</p>
{/if}

{if="$emaildomain == 'outlook.com'"}
<p class="well">
<a target="_blank"  href="https://outlook.com/">Jump to Outlook.com in a new window.</a>
</p>
{/if}



</div>
