@include('crud::fields.'.$field['template'])

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('after_scripts')
    <script>
        crud.field('{{ $field['name'] }}').onChange(function(field) {

            if (field.value && field.value != {{ $field['value'] ?? 0 }}) {
                let select_template_confirmation = confirm("@lang('backpack::pagemanager.change_template_confirmation')");
                if (select_template_confirmation == true) {
                    let field_name = field.name;
                    let search_params = new URLSearchParams(window.location.search);
                    $(".select_template").each(function(index) {
                        search_params.set(this.id, this.value)
                    });
                    search_params.set(field_name, field.value)
                    window.location.search = search_params.toString()
                }
            }
        }).change();
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
