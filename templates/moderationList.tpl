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
						<li{if $definitionID == 0} class="active"{/if}><a href="{link controller='ModerationList'}pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.type.all{/lang}</a></li>
						{foreach from=$availableDefinitions key=__definitionID item=definitionName}
							<li{if $definitionID == $__definitionID} class="active"{/if}><a href="{link controller='ModerationList'}definitionID={@$__definitionID}&pageNo={@$pageNo}&sortField={@$sortField}&sortOrder={@$sortOrder}{/link}">{lang}wcf.moderation.type.{$definitionName}{/lang}</a></li>
						{/foreach}
					</ul>
				</div>
			</li>
			
			{* assigned user *}
			<li class="menuGroup">
				<h1>{lang}wcf.moderation.filterByUser{/lang}</h1>
				<div class="menuGroupItems">
					
				</div>
			</li>
		</ul>
	</nav>	
{/capture}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.moderation.headline{/lang}</h1>
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
					<th class="columnID{if $sortField == 'id'} active{/if}"><a href="{link controller='ModerationList'}{if $definitionID}definitionID={@$definitionID}&{/if}pageNo={@$pageNo}&sortField=id&sortOrder={if $sortField == 'id' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'id'} <img src="{icon size='S'}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnTitle">{lang}wcf.moderation.title{/lang}</th>
					<th class="columnDate columnTime{if $sortField == 'time'} active{/if}"><a href="{link controller='ModerationList'}{if $definitionID}definitionID={@$definitionID}&{/if}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.time{/lang}{if $sortField == 'time'} <img src="{icon size='S'}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnUserID{if $sortField == 'reportingUsername'} active{/if}"><a href="{link controller='ModerationList'}{if $definitionID}definitionID={@$definitionID}&{/if}pageNo={@$pageNo}&sortField=reportingUsername&sortOrder={if $sortField == 'reportingUsername' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.reportedBy{/lang}{if $sortField == 'reportingUsername'} <img src="{icon size='S'}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnDate columnLastChangeTime{if $sortField == 'lastChangeTime'} active{/if}"><a href="{link controller='ModerationList'}{if $definitionID}definitionID={@$definitionID}&{/if}pageNo={@$pageNo}&sortField=lastChangeTime&sortOrder={if $sortField == 'lastChangeTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.lastChangeTime{/lang}{if $sortField == 'lastChangeTime'} <img src="{icon size='S'}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnAssignedUserID{if $sortField == 'assignedUsername'} active{/if}"><a href="{link controller='ModerationList'}{if $definitionID}definitionID={@$definitionID}&{/if}pageNo={@$pageNo}&sortField=assignedUsername&sortOrder={if $sortField == 'assignedUsername' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.moderation.assignedUser{/lang}{if $sortField == 'assignedUsername'} <img src="{icon size='S'}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnType">{lang}wcf.moderation.type{/lang}</th>
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=entry}
						<tr>
							<td>{#$entry->queueID}</td>
							<td><a href="{$entry->getLink()}">{$entry->getTitle()}</a></td>
							<td>{@$entry->time|time}</td>
							<td>{if $entry->userID}<a href="{link controller='User' id=$entry->userID}{/link}" class="userLink" data-user-id="{@$entry->userID}">{$entry->reportingUsername}</a>{else}{lang}wcf.global.guest{/lang}{/if}</td>
							<td>{if $entry->lastChangeTime}{@$entry->lastChangeTime|time}{/if}</td>
							<td>{if $entry->assignedUserID}<a href="{link controller='User' id=$entry->assignedUserID}{/link}" class="userLink" data-user-id="{@$entry->assignedUserID}">{$entry->assignedUsername}</a>{/if}</td>
							<td>{lang}wcf.moderation.type.foo{/lang}</td>
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