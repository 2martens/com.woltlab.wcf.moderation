{if $__wcf->user->userID && $__wcf->session->getPermission('mod.general.canUseModeration')}
	<li id="outstandingModeration" data-count="{#$__wcf->getModerationQueueManager()->getOutstandingModerationCount()}" data-title="{lang}wcf.moderation.moderation{/lang}">
		<a class="jsTooltip" href="{link controller='ModerationList'}{/link}" title="{lang}wcf.moderation.moderation{/lang}">
			<img src="{icon}warningInverse{/icon}" alt="" class="icon24" />
			<span class="invisible">{lang}wcf.moderation.moderation{/lang}</span>
			{if $__wcf->getModerationQueueManager()->getOutstandingModerationCount()}<span class="badge badgeInverse">{#$__wcf->getModerationQueueManager()->getOutstandingModerationCount()}</span>{/if}
		</a>
		<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Moderation.js"></script>
		<script type="text/javascript">
			//<![CDATA[
			$(function() {
				WCF.Language.addObject({
					'wcf.moderation.showAll': '{lang}wcf.moderation.showAll{/lang}'
				});
				
				new WCF.Moderation.UserPanel('{link controller='ModerationList'}{/link}');
			});
			//]]>
		</script>
	</li>
{/if}