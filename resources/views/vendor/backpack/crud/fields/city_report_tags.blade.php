@php
    $unique_users = $field['model']->groupBy('user_id');
    function randomColorChip()
    {
        $color = ['#82b1ff', '#ff8a65', '#81c784', '#ffb74d'];
        return $color[array_rand($color)];
    }
    function roleCity($managers)
    {
        $array = $managers->map(function ($manager) {
            return !empty($manager->role) ? $manager->role->humanOptions() : '-';
        });
        return implode(', ', $array->toArray());
    }
@endphp
<div class="city_report_tags px-3">
    @if (count($unique_users) == 0)
        <div>
            Нет установленных менеджеров
        </div>
    @endif
    @foreach ($unique_users as $manager_key => $managers)
        <div class="card my-3">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-lg-auto col-form-label">Пользователь</label>
                    <div class="col-lg-4">
                        <div class="input-group mb-2">
                            <input class="form-control" value="{{ $managers[0]?->user->name }}" disabled>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <a href="/user/{{ $manager_key }}/edit" class="btn btn-primary my-0 py-0 href"
                                        role="button" target="_blank">
                                        Открыть
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-auto col-form-label">Роль в городе</label>
                    <div class="col-lg-4">
                        <input class="form-control" value="{{ roleCity($managers) }}" disabled>
                    </div>
                </div>
                <div class="border-top my-3"></div>
                <div class="row">
                    @php
                        $block = 0;
                    @endphp
                    @foreach ($managers->where('group_type', '<>', '')->groupBy('group_type.value') as $group_key => $group)
                        @php
                            $block += 1;
                        @endphp
                        <div class="col-lg-6" style="margin-bottom: 30px;">
                            <div class="card mb-0" style="background: #3648f40a; height:100%">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-lg-auto col-form-label">Группа</label>
                                        <div class="col-lg-6">
                                            <div class="input-group mb-2">
                                                <input class="form-control"
                                                    value="{{ $group[0]->group_type?->humanOptions() }}" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @foreach ($group->where('role', '<>', '')->groupBy('role.value') as $role_key => $roles)
                                            @if (!empty($role_key))
                                                <div class="card">
                                                    <div class="card-header">
                                                        {{ !empty($roles[0]) ? $roles[0]->role->humanOptions() : '' }}
                                                    </div>
                                                    <div class="card-body" style="overflow: auto;">
                                                        @foreach ($roles as &$role)
                                                            <table class="table mb-2">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Категория</th>
                                                                        <th scope="col">Теги</th>
                                                                        <th scope="col">
                                                                            <div class="d-flex justify-content-end">
                                                                                <a href="/manager-city-user/{{ $role->id }}/edit"
                                                                                    class="btn btn-primary my-0 py-0 href"
                                                                                    role="button" target="_blank">
                                                                                    Открыть
                                                                                </a>
                                                                            </div>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($role->tags->groupBy('tag_category_id') as $row_key => $row)
                                                                        @php
                                                                            $class_hide = 'hide_group_' . $manager_key . '_' . $group_key . '_' . $role_key . '_' . $row_key;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div style="white-space: nowrap;">
                                                                                    {{ $row[0] && $row[0]->category ? $row[0]->category->name : '-' }}
                                                                                </div>
                                                                                @if (count($row) > 5)
                                                                                    <div class="btn btn-secondary my-0 py-1 href"
                                                                                        onclick="changeHideStatus('{{ $class_hide }}')">
                                                                                        Все теги
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                            <td colspan="2">
                                                                                @foreach ($row as $tag_k => $tag)
                                                                                    <div class="badge-outline badge-pill {{ $tag_k >= 5 ? 'default_hide ' . $class_hide : '' }}"
                                                                                        style="border-color: {{ randomColorChip() }}">
                                                                                        {{ $tag->name }}
                                                                                    </div>
                                                                                @endforeach
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($block == 0)
                        <div class="col-lg-12">
                            <p class="card-text text-center"> Нет назначенных групп</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('crud_fields_scripts')
    <script>
        function changeHideStatus(className) {
            if ($(`.${className}`).is(":visible")) {
                $(`.${className}`).hide();
            } else {
                $(`.${className}`).show();
            }
        }
    </script>
@endpush

<style>
    .city_report_tags {
        width: 100%;
    }

    .city_report_tags .card {
        width: 100%;
    }

    .href {
        font-size: 0.825rem;
    }

    .badge-outline {
        color: black;
        border: 1px solid #999;
        background-color: transparent;
        white-space: nowrap !important;
        margin: 4px;
        width: max-content;
        float: left;
    }

    .default_hide {
        display: none;
    }
</style>
