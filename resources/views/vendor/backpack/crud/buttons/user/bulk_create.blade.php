{{-- @if ($crud->hasAccess('bulkClone') && $crud->get('list.bulkActions')) --}}
<a href="javascript:void(0)" onclick="bulkCreate()" class="btn btn-sm btn-secondary">
    <i class="la la-copy"></i>
    Массовое создание графиков по дням недели
</a>
{{-- @endif --}}

@push('after_scripts')
    <script>
        if (typeof bulkCreate != 'function') {
            function bulkCreate() {
                {{-- let route = `{{ url($crud->route) }}/bulk-create}`; --}}
                {{-- document.location.href = route; --}}

                let form = document.createElement('form');
                form.action = `{{ url($crud->route) }}/bulk-create`;
                form.method = 'POST';
                let items = '<input type="hidden" name="_token" value="{{ csrf_token() }}" />';
                form.innerHTML = items;
                document.body.append(form);
                form.submit();
            }
        }
    </script>
@endpush
