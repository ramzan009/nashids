@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => backpack_url('dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.edit') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small>

            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                            class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }}
                        <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')

    <div class="row">
        <div class="{{ $crud->getEditContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <form method="GET"
                  action="{{ url($crud->route.'/bulk-floating-create-manage') }}"
                  class="form-create"
            >
                {{ csrf_field() }}
                {{ method_field('GET') }}

                <div class="card">
                    <div class="card-body row">
                        <div class="form-group col-sm-12" element="div" bp-field-wrapper="true" bp-field-name="month"
                             bp-field-type="month">
                            <label>Дата</label>
                            <input type="month" name="month" placeholder="ГГГГ-ММ" required=""
                                   class="form-control month-input"
                                   maxlength="7">
                            <p class="help-block">Если у вас не отображается календарь введите дату в формате:
                                ГГГГ-ММ</p>
                        </div>

                        <div class="form-group col-sm-12 ">
                            <label>Исполнители со сменным графиком работы</label>
                            <div class="row to-specialist-content">
                                <div class="col-sm-6">
                                    Для выбранного периода не найдены доступные исполнители со сменным графиком работы
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @include('crud::inc.form_save_buttons')

                <button type="submit" class="btn btn-success btn-send">
                    <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span data-value="save_and_back">Далее</span>
                </button>
            </form>
        </div>
    </div>


    <script>
        // XSRF-TOKEN для выполнения запроса
        const xsrfToken = document.body.querySelector('input[name="_token"]').value;

        // Блок в котором находятся исполнители
        const toSpecialistContent = document.querySelector('div.to-specialist-content');

        // Инпут даты
        const monthInput = document.querySelector('.month-input');

        // Кнопка отправки формы
        const btn = document.querySelector('.btn-send');

        monthInput.addEventListener('input', function (e) {
            // Введенная дата
            const selectedMonth = e.target.value;

            // Выполнение запроса происходит после введения 7 символов
            if (selectedMonth.length < 7) {
                return;
            }

            fetch('/to-specialist-work-schedule/fetch/floating-schedule-specialists', {
                method: 'POST',
                body: JSON.stringify({
                    month: selectedMonth
                }),
                headers: {
                    "X-CSRF-TOKEN": xsrfToken,
                    "Content-Type": "application/json",
                }
            })
                .then(res => res.json())
                .then((data) => {
                    btn.disabled = false;
                    clearContent();

                    if (data.length === 0) {
                        setContentInHTML('<div class="col-sm-6">Для выбранного периода не найдены доступные исполнители со сменным графиком работы</div>');
                        btn.disabled = true;
                        return;
                    }

                    // Вывод исполнителей на страницу
                    data.forEach(item => {
                        setContentInHTML(getSpecialistTemplate(item.id, item.name));
                    });
                })
                .catch((e) => {
                    alert('Вышла ошибка, пожалуйста, обратитесь к поддержке');
                })
        })

        function getSpecialistTemplate(value, content) {
            return `<div class="col-sm-4">
                      <div class="checkbox">
                        <label class="font-weight-normal"><input type="checkbox" name="toSpecialists[]" value="${value}"> ${content} </label>
                      </div>
                    </div>
                `;
        }

        function setContentInHTML(content) {
            toSpecialistContent.insertAdjacentHTML('beforeend', content);
        }


        function clearContent() {
            toSpecialistContent.innerHTML = '';
        }
    </script>

    <script>
        // Форма
        const form = document.querySelector('.form-create');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Получаем данные формы
            const formData = new FormData(form);

            // Проверяем заполнили ли поле toSpecialists, если да, то отправляем форму
            if (formData.has('toSpecialists[]')) {
                form.submit();
                return;
            }

            new Noty({
                type: "error",
                text: 'Выберите исполнителей для формирования графика'
            }).show();
        });

    </script>

@endsection
