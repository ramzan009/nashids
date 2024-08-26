@php
    $field['allows_null'] = $field['allows_null'] ?? $crud->model::isColumnNullable($field['name']);
    $field['value'] = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';
@endphp
<!-- select from array -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <input
        name="{{ $field['name'] }}"
        list="{{ $field['name'] }}"
        value="{{ $field['value'] }}"
        @include('crud::fields.inc.attributes')
    >
    <datalist id="{{ $field['name'] }}">
        @if (count($field['options']))
            @foreach ($field['options'] as $value)
                <option value="{{ $value }}"></option>
            @endforeach
        @endif
    </datalist >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')
