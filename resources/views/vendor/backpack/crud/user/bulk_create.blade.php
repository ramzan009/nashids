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
    <style>
        .modal-backdrop.fade {
            display: none;
        }

        #infoModal:after {
            content: '';
            width: inherit;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0.2;
            z-index: -1;
            height: inherit;
            background: #000;
        }
    </style>

    <div class="row">
        <div class="{{ $crud->getEditContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <form method="post"
                  action="{{ url($crud->route.'/bulk-create') }}"
                  @if ($crud->hasUploadFields('update'))
                      enctype="multipart/form-data"
                  @endif
                  class="form-create"
            >
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}

                @if ($crud->model->translationEnabled())
                    <div class="mb-2 text-right">
                        <!-- Single button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                {{trans('backpack::crud.language')}}
                                : {{ $crud->model->getAvailableLocales()[request()->input('locale')?request()->input('locale'):App::getLocale()] }}
                                &nbsp; <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                    <a class="dropdown-item"
                                       href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                @if(view()->exists('vendor.backpack.crud.form_content'))
                    @include('vendor.backpack.crud.form_content', ['fields' => $crud->fields(), 'action' => 'moderate'])
                @else
                    @include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'moderate'])
                @endif

                @include('crud::inc.form_save_buttons')

                <button type="submit" class="btn btn-success btn-send">
                    <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span data-value="save_and_back">Сохранить и выйти</span>
                </button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Оповещение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="word-wrap: break-word;">
                    <p>
                        По городам <span class="data-cities" style="font-weight: bold;"></span> уже сформированы графики
                        в выбранный период
                    </p>
                    <p>Требуется переформировать графики?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary modal-accept-button">Подтвердить</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        // XSRF-TOKEN для выполнения запроса
        const xsrfToken = document.body.querySelector('input[name="_token"]').value;

        // Кнопка отправки формы ("Сохранить и выйти")
        const btn = document.querySelector('.btn-send');

        // Форма со всеми полями
        const form = document.querySelector('.form-create');

        // Span в модалке, в который будут вставляться список городов
        const modalSpan = document.querySelector('.data-cities');

        // Кнопка "Подтвердить" в модалке
        const modalAcceptButton = document.querySelector('.modal-accept-button');

        // Обрабатываем нажатие
        btn.addEventListener('click', function (e) {
            // Не даем отправить форму
            e.preventDefault();

            // Выбранные города (чекбоксы). Возвращает json
            const selected_cities = crud.field('gkCitiesFlex');

            // Выбранная дата (в виде Y-m)
            const month = crud.field('month');
            if (selected_cities.value === '[]' || month.value === '') {
                return
            }


            // Запрос для проверки есть ли такие графики с выбранными городами (selected_cities) на выбранный месяц (month)
            fetch('/to-specialist-work-schedule/fetch/to-specialist-schedules', {
                method: "POST",
                body: JSON.stringify({
                    // Парсим selected_cities, чтобы превратить из json в массив
                    cities: JSON.parse(selected_cities.value),
                    date: month.value
                }),
                headers: {
                    "X-CSRF-TOKEN": xsrfToken, "Content-Type": "application/json",
                }
            })
                .then(response => response.json())
                .then(response => {
                    data = response.data;

                    console.log(data);

                    // Если пришло true, значит на данный месяц есть созданные графики (month)
                    if (data.has_schedules === true) {
                        // Показываем модалку
                        $('#infoModal').modal('show');

                        // добавляем туда информацию о том, на какие города эти графики были созданы
                        modalSpan.innerHTML = data.cities_with_schedules.join(',');
                    } else {
                        // Если пришло false, то значит графиков нет на данный период и можно отправить запрос
                        form.submit();
                    }
                });
        });

        // Если нажали на "Подтвердить" в модалке, то отправляем запрос на массовое создание
        modalAcceptButton.addEventListener('click', () => form.submit());
    </script>

@endsection

