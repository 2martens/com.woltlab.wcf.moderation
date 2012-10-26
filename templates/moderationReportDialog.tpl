<fieldset>
	<legend>{lang}wcf.moderation.report.reportContent{/lang}</legend>
	
	{if $alreadyReported}
		{lang}wcf.moderation.report.alreadyReported{/lang}
	{else}
		{lang}wcf.moderation.report.reportObject{/lang}
		
		<textarea cols="40" rows="5" class="jsReportMessage marginTop"></textarea>
		
		<div class="formSubmit">
			<button class="jsSubmitReport" accesskey="s">{lang}wcf.global.button.submit{/lang}</button>
		</div>
	{/if}
</fieldset>