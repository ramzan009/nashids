<!-- mask input -->
@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

@if(isset($field['prefix']) || isset($field['suffix']))
    <div class="input-group"> @endif
        @if(isset($field['prefix']))
            <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            data-init-function="bpFieldInitMaskField_{{$field['mask_type']}}"
            @include('crud::fields.inc.attributes')
        >
        @if(isset($field['suffix']))
            <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
        @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_scripts')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8-beta.17/jquery.inputmask.min.js"></script>
@endpush

@if(isset($field['mask_type']))
    @push('crud_fields_scripts')
        <script>
            function bpFieldInitMaskField_email(element) {
                $(element).inputmask({
                    alias: "email"
                });
            }

            function bpFieldInitMaskField_phone(element) {
                $(element).inputmask({
                    "mask": "+7(999) 999-9999",
                    greedy: false,
                    removeMaskOnSubmit: true,
                });
            }

            function bpFieldInitMaskField_phoneAlternator(element) {

                $(element).inputmask({
                    "mask": ["999", "+7(999) 999-9999"],
                    greedy: false,
                    removeMaskOnSubmit: true,
                });
            }
        </script>
    @endpush
@endif
