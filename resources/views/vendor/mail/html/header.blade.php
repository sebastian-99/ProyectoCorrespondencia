<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('images/Correoutvt.jpg')}}" class="logo" alt="UTVT Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
