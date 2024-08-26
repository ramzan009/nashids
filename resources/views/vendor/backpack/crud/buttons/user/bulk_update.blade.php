{{-- @if ($crud->hasAccess('bulkClone') && $crud->get('list.bulkActions')) --}}
<a href="javascript:void(0)" onclick="bulkCloneEntries(this)" class="btn btn-sm btn-secondary bulk-button">
    <i class="la la-copy"></i>
    Массовое изменение времени
</a>
{{-- @endif --}}

@push('after_scripts')
    <script>
        if (typeof bulkCloneEntries != 'function') {
            function bulkCloneEntries(button) {
                if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length <= 1) {
                    new Noty({
                        type: "warning",
                        text: `<strong>{!! trans('backpack::crud.bulk_no_entries_selected_title') !!}</strong><br>
					Пожалуйста, выберите  не менее <strong>2-х элементов</strong>, чтобы выполнить массовое
					действие с ними`
                    }).show();
                    return;
                }

                let message = "Произвести массовое обновление :number записи(ей)";
                message = message.replace(":number", crud.checkedItems.length);

                // показать сообщение подтверждения
                swal({
                    title: "{!! trans('backpack::base.warning') !!}",
                    text: message,
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "{!! trans('backpack::crud.cancel') !!}",
                            value: null,
                            visible: true,
                            className: "bg-secondary",
                            closeModal: true,
                        },
                        delete: {
                            text: "Обновить",
                            value: true,
                            visible: true,
                            className: "bg-primary",
                        }
                    },
                }).then((value) => {
                    if (value) {
                        {{-- let route = `{{ url($crud->route) }}/moderate?item[]=${crud.checkedItems.join('&item[]=')}`; --}}
                        {{-- document.location.href = route; --}}

                        let form = document.createElement('form');
                        form.action = `{{ url($crud->route) }}/moderate`;
                        form.method = 'POST';
                        let items = '<input type="hidden" name="_token" value="{{ csrf_token() }}" />';
                        for (let i = 0; i < crud.checkedItems.length; i++) {
                            items += `<input name="item[]" type="hidden" value="${crud.checkedItems[i]}">`;
                        }
                        form.innerHTML = items;
                        document.body.append(form);
                        form.submit();
                    }
                });
            }
        }
    </script>
@endpush
