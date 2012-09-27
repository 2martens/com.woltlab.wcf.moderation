{include file='documentHeader'}

<head>
	<title>{lang}wcf.moderation.moderation{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{capture assign='sidebar'}
	<nav id="sidebarContent" class="sidebarContent">
		<ul>
			{* moderation type *}
			<li class="menuGroup">
				<h1>{lang}wcf.moderation.filterByType{/lang}</h1>
				<div class="menuGroupItems">
					<ul>
						<li{if $definitionID == 0} class="active"{/if}><a href="{link controller='ModerationList'}definitionID=0&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.type.all{/lang}</a></li>
						{foreach from=$availableDefinitions key=__definitionID item=definitionName}
							<li{if $definitionID == $__definitionID} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$__definitionID}&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.type.{$definitionName}{/lang}</a></li>
						{/foreach}
					</ul>
				</div>
			</li>
			
			{* assigned user *}
			<li class="menuGroup">
				<h1>{lang}wcf.moderation.filterByUser{/lang}</h1>
				<div class="menuGroupItems">
					<ul>
						<li{if $assignedUserID == -1} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID=-1&status={@$status}&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.filterByUser.allEntries{/lang}</a></li>
						<li{if $assignedUserID == 0} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID=0&status={@$status}&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.filterByUser.nobody{/lang}</a></li>
						<li{if $assignedUserID == $__wcf->getUser()->userID} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$__wcf->getUser()->userID}&status={@$status}&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.filterByUser.myself{/lang}</a></li>
					</ul>
				</div>
			</li>
			
			{* status *}
			<li class="menuGroup">
				<h1>{lang}wcf.moderation.status{/lang}</h1>
				<div class="menuGroupItems">
					<ul>
						<li{if $status == -1} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status=-1&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.status.all{/lang}</a></li>
						<li{if $status == 2} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status=2&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.status.done{/lang}</a></li>
					</ul>
				</div>
			</li>
		</ul>
	</nav>	
{/capture}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.moderation.moderation{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='ModerationList' link="id=$definitionID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
</div>

{hascontent}
	<div class="marginTop tabularBox tabularBoxTitle shadow">
		<hgroup>
			<h1>{lang}wcf.moderation.items{/lang} <span class="badge badgeInverse">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID{if $sortField == 'queueID'} active{/if}"><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField=queueID&sortOrder={if $sortField == 'queueID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'queueID'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnTitle">{lang}wcf.moderation.title{/lang}</th>
					<th class="columnDate columnTime{if $sortField == 'time'} active{/if}"><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.time{/lang}{if $sortField == 'time'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnUserID{if $sortField == 'username'} active{/if}"><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.username{/lang}{if $sortField == 'username'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnDate columnLastChangeTime{if $sortField == 'lastChangeTime'} active{/if}"><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField=lastChangeTime&sortOrder={if $sortField == 'lastChangeTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.lastChangeTime{/lang}{if $sortField == 'lastChangeTime'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnAssignedUserID{if $sortField == 'assignedUsername'} active{/if}"><a href="{link controller='ModerationList'}definitionID={@$definitionID}&assignedUserID={@$assignedUserID}&status={@$status}&pageNo={@$pageNo}&sortField=assignedUsername&sortOrder={if $sortField == 'assignedUsername' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.assignedUser{/lang}{if $sortField == 'assignedUsername'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					{if !$definitionID}<th class="columnText columnType">{lang}wcf.moderation.type{/lang}</th>{/if}
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=entry}
						<tr>
							<td>{#$entry->queueID}</td>
							<td><a href="{$entry->getLink()}">{$entry->getTitle()}</a></td>
							<td>{@$entry->time|time}</td>
							<td>{if $entry->userID}<a href="{link controller='User' id=$entry->userID}{/link}" class="userLink" data-user-id="{@$entry->userID}">{$entry->username}</a>{else}{lang}wcf.global.guest{/lang}{/if}</td>
							<td>{if $entry->lastChangeTime}{@$entry->lastChangeTime|time}{/if}</td>
							<td>{if $entry->assignedUserID}<a href="{link controller='User' id=$entry->assignedUserID}{/link}" class="userLink" data-user-id="{@$entry->assignedUserID}">{$entry->assignedUsername}</a>{/if}</td>
							{if !$definitionID}<td>{lang}wcf.moderation.type.{@$definitionNames[$entry->objectTypeID]}{/lang}</td>{/if}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.moderation.noItems{/lang}</p>
{/hascontent}

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>