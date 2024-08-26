<!-- Select template field. Used in Backpack/PageManager to redirect to a form with different fields if the template changes. A fork of the select_from_array field with an extra ID and an extra javascript file. -->
@include('crud::fields.inc.wrapper_start')

<label>{{ $field['label'] }}</label>
<select class="form-control select_template" id="{{ !empty($field['parameter']) ? $field['parameter'] : 'page_template' }}"
    @foreach ($field as $attribute => $value) @if (!is_array($value))
    {{ $attribute }}="{{ $value }}" @endif
    @endforeach
    @if(in_array('disabled', $field['wrapperAttributes'] ?? [])) disabled @endif 
    >

    @if (isset($field['allows_null']) && $field['allows_null'] == true)
        <option value="">-</option>
    @endif

    @if (count($field['options']))
        @foreach ($field['options'] as $key => $value)
            <option value="{{ $key }}" @if (isset($field['value']) && $key == $field['value']) selected @endif>{{ $value }}
            </option>
        @endforeach
    @endif
</select>

@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}


{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    <!-- select_template crud field JS -->
    <script>
        jQuery(document).ready(function($) {
            $("#{{ !empty($field['parameter']) ? $field['parameter'] : 'page_template' }}").change(function(e) {
                let select_template_confirmation = confirm("@lang('backpack::pagemanager.change_template_confirmation')");
                if (select_template_confirmation == true) {
                    let field_name = e.target.id;
                    let search_params = new URLSearchParams(window.location.search);
                    $(".select_template").each(function(index) {
                        search_params.set(this.id, this.value)
                    });
                    search_params.set(field_name, e.target.value)
                    window.location.search = search_params.toString()
                }
            });
        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
