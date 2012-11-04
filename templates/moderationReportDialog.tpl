{if $alreadyReported}
	<p class="info">{lang}wcf.moderation.report.alreadyReported{/lang}</p>
{else}
	<fieldset>
		<legend><label for="reason">{lang}wcf.moderation.report.reason{/lang}</label></legend>
		
		<dl class="wide" style="max-width: 600px;">
			<dd>
				<textarea id="reason" required="true" cols="60" rows="10" class="jsReportMessage marginTop"></textarea>
				<small>{lang}wcf.moderation.report.reason.description{/lang}</small>
			</dd>
		</dl>	
	</fieldset>
	
	<div class="formSubmit">
		<button class="jsSubmitReport buttonPrimary" accesskey="s">{lang}wcf.global.button.submit{/lang}</button>
	</div>
{/if}