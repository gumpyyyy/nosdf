<div id="statistics" class="tabelem paddedtop" title="{$c->__("statistics.title")}">
    <ul class="list">
        <li class="title">
            <a class="action">{$c->__('statistics.since')}</a>
            {$c->__('statistics.sessions')} - {$sessions|count}
        </li>
        {loop="$sessions"}
            <li>
                {if="isset($value->start)"}
                <a class="action">{$c->getTime($value->start)}</a>
                {/if}
                <a>{$value->username}@{$value->host} - {$value->domain}</a>
            </li>
        {/loop}
    </ul>

    <h1><i class="fa fa-line-chart"></i> {$c->__('statistics.subscriptions')}</h1>
    <img src="{$cache_path}monthly.png">
    <img src="{$cache_path}monthly_cumulated.png">
</div>
