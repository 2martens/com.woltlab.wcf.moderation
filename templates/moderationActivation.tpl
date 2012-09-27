{include file='documentHeader'}

<head>
	<title>{lang}wcf.moderation.activation{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
	
	<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Moderation.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Moderation.Activation.Management({@$queue->queueID}, '{link controller='ModerationList'}{/link}');
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.moderation.activation{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='ModerationList'}{/link}" title="{lang}wcf.moderation.moderation{/lang}" class="button"><img src="{icon}list{/icon}" alt="" class="icon24" /> <span>{lang}wcf.moderation.moderation{/lang}</span></a></li>
			{event name='largeButtonsTop'}
		</ul>
	</nav>
</div>

<form method="post" action="{link controller='ModerationReport' id=$queue->queueID}{/link}" class="container containerPadding marginTop">
	<fieldset>
		<legend>{lang}wcf.moderation.activation.details{/lang}</legend>
		
		<dl>
			<dt>{lang}wcf.global.objectID{/lang}</dt>
			<dd>{#$queue->queueID}</dd>
		</dl>
		{if $queue->lastChangeTime}
			<dl>
				<dt>{lang}wcf.moderation.lastChangeTime{/lang}</dt>
				<dd>{@$queue->lastChangeTime|time}</dd>
			</dl>
		{/if}
		<dl>
			<dt>{lang}wcf.moderation.assignedUser{/lang}</dt>
			<dd>
				<ul>
					{if $assignedUserID && ($assignedUserID != $__wcf->getUser()->userID)}
						<li><label><input type="radio" name="assignedUserID" value="{@$assignedUserID}" checked="checked" /> {$queue->assignedUsername}</label></li>
					{/if}
					<li><label><input type="radio" name="assignedUserID" value="{@$__wcf->getUser()->userID}"{if $assignedUserID == $__wcf->getUser()->userID} checked="checked"{/if} /> {$__wcf->getUser()->username}</label></li>
					<li><label><input type="radio" name="assignedUserID" value="0"{if !$assignedUserID} checked="checked"{/if} /> {lang}wcf.moderation.assignedUser.nobody{/lang}</label></li>
				</ul>
			</dd>
		</dl>
		{if $queue->assignedUser}
			<dl>
				
				<dd><a href="{link controller='User' id=$assignedUserID}{/link}" class="userLink" data-user-id="{@$assignedUserID}">{$queue->assignedUsername}</a></dd>
			</dl>
		{/if}
		<dl>
			<dt>{lang}wcf.moderation.comment{/lang}</dt>
			<dd><textarea name="comment" rows="4" cols="40">{$comment}</textarea></dd>
		</dl>
		
		<div class="formSubmit">
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" />
		</div>
	</fieldset>
</form>

<fieldset class="marginTop">
	<legend>{lang}wcf.moderation.activation.content{/lang}</legend>
	
	<div>
		{@$disabledContent}
	</div>
	
	<div class="formSubmit">
		<input type="button" value="{lang}wcf.moderation.activation.enableContent{/lang}" id="enableContent" />
		<input type="button" value="{lang}wcf.moderation.activation.removeContent{/lang}" id="removeContent" />
	</div>
</fieldset>

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='ModerationList'}{/link}" title="{lang}wcf.moderation.moderation{/lang}" class="button"><img src="{icon}list{/icon}" alt="" class="icon24" /> <span>{lang}wcf.moderation.moderation{/lang}</span></a></li>
			{event name='largeButtonsTop'}
		</ul>
	</nav>
</div>

{include file='footer'}

</body>
</html>