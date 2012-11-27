{foreach from=$queues item=queue}
	<li>
		<a href="{@$queue->getLink()}" class="box24">
			<div class="framed">
				{@$queue->getUserProfile()->getAvatar()->getImageTag(24)}
			</div>
			<hgroup>
				<h1>{$queue->getAffectedObject()->getTitle()}</h1>
				<h2><small>{$queue->getAffectedObject()->getUsername()} - {@$queue->getAffectedObject()->getTime()|time}</small></h2>
			</hgroup>
		</a>
	</li>
{/foreach}