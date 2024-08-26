<!-- used for heading, separators, etc -->
@php
    $column['escaped'] = $column['escaped'] ?? false;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';

    if($field['value'] instanceof \Closure) {
        $field['value'] = $field['value']($entry);
    }

@endphp

@include('crud::fields.inc.wrapper_start')
	{!! $field['value'] !!}
@include('crud::fields.inc.wrapper_end')
