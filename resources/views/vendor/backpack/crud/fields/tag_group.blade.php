<?php

/**
 * @var array $field
 */

?>

    <!-- checklist -->
@php
    $key_attribute = (new $field['model'])->getKeyName();
    $field['attribute'] = $field['attribute'] ?? (new $field['model'])->identifiableAttribute();
    $field['number_of_columns'] = $field['number_of_columns'] ?? 3;

    // calculate the checklist options
    $field['options'] = $field['model']::all()->groupBy($field['group_by']);
    $values = isset($field['value']) ? $field['value']->pluck('id')->toArray() : [];
    //entity

    // calculate the value of the hidden input
    $field['value'] = old_empty_or_null($field['name'], []) ??  $field['value'] ?? $field['default'] ?? [];
    if(!empty($field['value'])) {
        if (is_a($field['value'], \Illuminate\Support\Collection::class)) {
            $field['value'] = ($field['value'])->pluck($key_attribute)->toArray();
        } elseif (is_string($field['value'])){
          $field['value'] = json_decode($field['value']);
        }
    }

    // define the init-function on the wrapper
    $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

<input type="hidden" value='@json($field['value'])' name="{{ $field['name'] }}">
<div>
    @foreach ($field['options'] as $keyGroup => $optionsGroup)
        @php
            $group_ids = $optionsGroup->pluck('id')->toArray();
            if (!empty($field['select_all'])) {
                $is_all_select = count(array_diff($group_ids, $field['value'])) == 0;
            }
            $count = count(array_intersect($group_ids, $values));
        @endphp
        <details {{$count ? 'open' : ''}} class="mb-1">
            <summary>
                <b>{{ $keyGroup ? trans('search::tag_group.' . $keyGroup) : 'Без блока' }}</b>
                <span>({{ $count }}/{{ $optionsGroup->count() }})</span>
                @if (!empty($field['select_all']))
                    <div class="btn {{ $is_all_select ? 'btn-danger' : 'btn-primary' }} my-0 py-0"
                        style="font-size: 0.825rem;" onclick="changeAll(this,{{ json_encode($group_ids) }});return false">
                        Все
                    </div>
                @endif
            </summary>
            <div class="row"> 

                @foreach ($optionsGroup as $option)

                    <div class="col-sm-{{ intval(12/$field['number_of_columns']) }}">
                        <div class="checkbox">
                            <label class="font-weight-normal">
                                <input
                                    type="checkbox"
                                    value="{{ $option[$key_attribute] }}"
                                > {{ $option[$field['attribute']] }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </details>
    @endforeach
</div>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    @loadOnce('bpFieldInitChecklist')
    <script>
        function bpFieldInitChecklist(element) {
            var hidden_input = element.find('input[type=hidden]');
            var selected_options = JSON.parse(hidden_input.val() || '[]');
            var checkboxes = element.find('input[type=checkbox]');
            element.find('.row');
// set the default checked/unchecked states on checklist options
            checkboxes.each(function () {
                var id = $(this).val();

                if (selected_options.map(String).includes(id)) {
                    $(this).prop('checked', 'checked');
                } else {
                    $(this).prop('checked', false);
                }
            });

            // when a checkbox is clicked
            // set the correct value on the hidden input
            checkboxes.click(function () {
                var newValue = [];

                checkboxes.each(function () {
                    if ($(this).is(':checked')) {
                        var id = $(this).val();
                        newValue.push(id);
                    }
                });

                hidden_input.val(JSON.stringify(newValue));
                console.log(hidden_input)
            });
        }

        function changeAll(e, array) {
            if (!$(e) || typeof array != 'object') return;
            let selected_all = $(e).hasClass('btn-primary');

            (array || []).forEach(item => {
                let checkbox = $(`input[value="${item}"]`);
                if ((checkbox.is(':checked') && !selected_all) || (!checkbox.is(':checked') && selected_all)) {
                    $(`input[value="${item}"]`).click();
                }
            });

            if (selected_all) {
                $(e).removeClass('btn-primary').addClass('btn-danger');
            } else {
                $(e).addClass('btn-primary').removeClass('btn-danger');
            }
        }
    </script>
    @endLoadOnce
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
