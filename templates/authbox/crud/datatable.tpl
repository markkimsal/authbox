{if $columns[0]|is_array}
{$coldef=true}
{else}
{$coldef=false}
{/if}

<table class="dataTable display" width="100%" data-loadurl="{$loadUrl}" data-saveurl="{$saveUrl}">
	<thead>
	<tr>
	{foreach $columns as $item}
		{if $item|is_array}
		<th class="{{$item.class}}">
		{{$item.name}}
		</th>
		{else}
		<th>
		{{$item}}
		</th>
		{/if}
	{/foreach}
	</tr>
	</thead>

	<tbody>
	{foreach $rows as $item}
		<tr>
		{if $coldef}
		{foreach $item as $value}
			<td class="{$columns[$value@index].class}" >
			{$value}
			</td>
		{/foreach}
		{else}
		{foreach $item as $value}
			<td>
			{$value}
			</td>
		{/foreach}
		{/if}
		</tr>
	{/foreach}
	</tbody>
</table>

