<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation"
       style="margin-top: {{ $space ?? '30px' }}; margin-bottom: {{ $space ?? '30px' }};">
<tr>
<td align="{{ $align ?? 'center' }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align ?? 'center' }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:{{ $width ?? 'auto' }};">
<tr>
<td align="center">
<a href="{{ $url }}" class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener" style="width:{{ $width ?? 'auto' }};">{{ $slot }}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
