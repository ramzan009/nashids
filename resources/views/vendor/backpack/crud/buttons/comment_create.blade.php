{{-- @if ($crud->hasAccess('bulkClone') && $crud->get('list.bulkActions')) --}}
<a href="javascript:void(0)" onclick="commentCreateEntries(this)" class="btn btn-sm btn-outline-primary seed-model">
    <i class="la la-plus"></i> комментарий
</a>

{{-- @endif --}}

@push('after_scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (typeof commentCreateEntries != 'function') {
            function commentCreateEntries() {
                Swal.fire({
                    title: `Добавление комментария`,
                    text: "Текст комментария",
                    input: 'textarea',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Добавить',
                    denyButtonText: "{!! trans('backpack::crud.cancel') !!}",
                    preConfirm: (textComment) => {
                        let myHeaders = new Headers();
                        myHeaders.append("Content-Type", "application/json");
                        let raw = JSON.stringify({
                            _token: '{{csrf_token()}}',
                            model_id: {{request()->id}},
                            comment: textComment,
                        });

                        return fetch(`{{url($crud->route)}}/comment-create?_token={{csrf_token()}}`, {
                            method: 'PUT',
                            body: raw,
                            headers: myHeaders
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Не удалось выполнить операцию. Обновите страницу и повторите попытку')
                                }
                                return response.json()
                            })
                            .then(response => {
                                if (response.result) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Комментарий добавлен',
                                        showConfirmButton: false,
                                        timer: 2500
                                    })
                                    setTimeout(() => location.reload(), 2500)
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Ошибка',
                                        text: response['msg'] ?? 'Не удалось выполнить операцию. Обновите страницу и повторите попытку',
                                        showConfirmButton: false,
                                        timer: 2500
                                    })
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ошибка',
                                    text: response['msg'] ?? 'Не удалось выполнить операцию. Обновите страницу и повторите попытку',
                                    showConfirmButton: false,
                                    timer: 2500
                                })
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            }
        }
    </script>
@endpush
