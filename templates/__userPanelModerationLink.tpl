{if $__wcf->user->userID && $__wcf->session->getPermission('mod.general.canUseModeration')}
	<li>
		<a class="jsTooltip" href="{link controller='ModerationList'}{/link}" title="{lang}wcf.moderation.moderation{/lang}">
			<img src="{icon}warningInverse{/icon}" alt="" class="icon24" />
			<span class="invisible">{lang}wcf.moderation.moderation{/lang}</span>
			{if $__wcf->getModerationQueueManager()->getOutstandingModerationCount()}<span class="badge badgeInverse">{#$__wcf->getModerationQueueManager()->getOutstandingModerationCount()}</span>{/if}
		</a>
	</li>
{/if}