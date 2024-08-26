@php use Carbon\CarbonInterval; @endphp
@extends(backpack_view('blank'))

@php
    /** @var \Illuminate\Support\Carbon $month*/
    /** @var \Illuminate\Support\Collection $to_specialists*/

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
        .table tbody tr .date_picker {
            display: flex;
            align-content: center;
        }

        .form-check label {
            margin: 0 5px;
        }

        .weekend {
            color: red;
        }

        .grey {
            color: grey;
        }

        .td-margin {
            padding-top: 16px;
        }

        .has-schedule {
            background: #C0E3C2;
        }

        .has-work {
            background: #FFDBA6 !important;
        }

        .hint .container {
            margin-bottom: 10px;
            display: flex;
        }

        .hint .container .green-block {
            width: 60px;
            height: 25px;
            background: #C0E3C2;
        }

        .hint .container .yellow-block {
            width: 60px;
            height: 25px;
            background: #FFDBA6;
        }

        .hint .explanation {
            margin-left: 10px;
        }
    </style>

    <div class="row">
        <div class="col-md-12 bold-labels">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <form method="POST"
                  action="{{ url($crud->route.'/bulk-floating-create-manage') }}"
                  class="form-create"
            >
                {{ csrf_field() }}
                {{ method_field('POST') }}

                <input type="hidden" name="month" value="{{ $month->format('Y-m-d') }}">
                <div class="card">
                    <div class="card-body row">

                        {{-- Выбор режима формирования для исполнителей--}}
                        <div class="form-group col-sm-8">
                            <h3>Исполнители со сменным типом графика</h3>
                            <table class="table table-borderless to-specialist-schedule-configure">
                                <tbody>
                                @foreach($to_specialists as $to_specialist)
                                    <tr data-specialist-id="{{ $to_specialist->id }}">
                                        <td>
                                            <div class="td-margin">
                                                {{ $to_specialist->userName }}
                                                <b>({{ $to_specialist->count_work_days}}
                                                    /{{$to_specialist->count_weekend_days}})</b>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-check form-check-inline td-margin">
                                                @php
                                                    // Смотрим есть ли у исполнителя сформированный график на прошлый месяц
                                                    $has_last_month_schedule = $to_specialist->schedules
                                                        ->where('date', '<=', $month->toImmutable()->setTime(0,0)->subMonth()->endOfMonth()->format('Y-m-d H:i:s'))
                                                        ->where('date', '>=', $month->toImmutable()->setTime(0,0)->subMonth()->startOfMonth()->format('Y-m-d H:i:s'))
                                                        ->isNotEmpty();
                                                @endphp

                                                <input type="radio"
                                                       @if(!$has_last_month_schedule)
                                                           disabled
                                                       @endif
                                                       name="toSpecialists[{{ $to_specialist->id }}][type]"
                                                       value="last_month"
                                                >
                                                <label for="" @if(!$has_last_month_schedule) class="grey" @endif>Продолжить
                                                    предыдущий месяц</label>
                                            </div>
                                        </td>

                                        <td class="date_picker">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="toSpecialists[{{ $to_specialist->id }}][type]"
                                                       value="start_by">
                                                <label for="">Начать с </label>
                                            </div>
                                            <div class="form-group col-md-8" element="div" bp-field-wrapper="true"
                                                 bp-field-type="date">
                                                <input type="date" disabled class="form-control"
                                                       name="toSpecialists[{{ $to_specialist->id }}][start_by_date]">
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{-- График работы--}}
                        <div class="form-group col-sm-12">
                            <label>График работы на {{ $month->isoFormat('MMMM YYYY') }}</label>
                            <table class="table table-bordered to-specialist-schedule">
                                <thead>
                                <tr>
                                    <th></th>
                                    @php
                                        // Период дней на выбранный месяц ($month)
                                        $period = CarbonInterval::days(1)->toPeriod($month->format('Y-m-d'), $month->toImmutable()->endOfMonth()->format('Y-m-d'));
                                    @endphp

                                    {{-- Если день выходной, отмечаем красным --}}
                                    @foreach($period as $period_item)
                                        <th @if($period_item->isWeekend()) class="weekend"
                                            @endif data-date="{{ $period_item->format('Y-m-d') }}">
                                            {{ $period_item->format('d') }}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($to_specialists as $to_specialist)

                                    <tr data-specialist-id="{{ $to_specialist->id }}">
                                        <td> {{ $to_specialist->userName }}</td>

                                        @foreach($period as $period_item)
                                            @php
                                                // Смотрим есть ли уже созданный график у исполнителя на текущий день
                                                $has_schedule = $to_specialist
                                                            ->schedules
                                                            ->where('date', $period_item->format('Y-m-d H:i:s'))
                                                            ->isNotEmpty();

                                                // Смотрим есть ли работа наряда на текущий день
                                                $has_work = $to_specialist
                                                            ->orderWork
                                                            ->where('date', $period_item->format('Y-m-d H:i:s'))
                                                            ->isNotEmpty();

                                                $classes = $has_schedule ? "has-schedule day" : "day";
                                                $classes .= $has_work ? " has-work" : "";
                                            @endphp

                                            <td class="{{ $classes }}" data-date="{{ $period_item->format('Y-m-d') }}">
                                                <input
                                                    type="checkbox"
                                                    name="toSpecialists[{{ $to_specialist->id }}][schedule][{{ $period_item->format('Y-m-d') }}]"
                                                    @if($has_schedule) checked @endif
                                                >
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group col-sm-8 hint">
                            <div class="container">
                                <div class="green-block"></div>
                                <div class="explanation">- График работы был ранее создан вручную. При снятии отметки
                                    график на этот день будет удален.
                                </div>
                            </div>
                            <div class="container">
                                <div class="yellow-block"></div>
                                <div class="explanation">- Запланирована активная работа для исполнителя. Для снятия
                                    отметки с этого дня требуется перенести работы с исполнителя.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <button type="submit" class="btn btn-success btn-send">
                    <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span data-value="save_and_back">Сохранить и Выйти</span>
                </button>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script>
        @php
            // Исполнители с графиком прошлого месяца
           $to_specialists->transform(function($to_specialist) use ($month) {
                $to_specialist->setAttribute(
                        'last_month_schedules',
                         $to_specialist->schedules
                            ->where('date', '>=', $month->toImmutable()->subMonth()->startOfMonth()->format('Y-m-d H:i:s'))
                            ->where('date', '<=', $month->toImmutable()->subMonth()->endOfMonth()->format('Y-m-d H:i:s'))
                            ->unique('date')
                            ->values()
                );
                return $to_specialist;
            });
        @endphp

        // Исполнители
        const specialists = JSON.parse('{!! $to_specialists->toJson() !!}');

        // Блок в котором настраивается формирование графиков
        const manageBlock = document.querySelector('.to-specialist-schedule-configure tbody');

        // Месяц на который генерируем график
        const generateMonth = moment('{{ $month->format('Y-m-d') }}');

        // Прошлый месяц по дате на которую генерируем график
        const lastMonth = generateMonth.clone().month(generateMonth.month() - 1);

        // Блок в котором находятся все ряды с чекбоксами для исполнителей пользователей
        const scheduleDiv = document.querySelector('.to-specialist-schedule');

        // Проход по всем рядам
        manageBlock.querySelectorAll('tr').forEach((tr, key) => {

            // ID обрабатываемого специалиста
            const toSpecialistId = tr.dataset.specialistId;

            // Радио-кнопки "Продолжить пред. месяц" и "Начать с"
            const radios = tr.querySelectorAll('td input[type="radio"]');

            // Обработка на изменение радио-инпутов
            radios.forEach((radio) => {
                // Input календаря
                const dateInput = tr.querySelector('.date_picker input[type="date"]');

                //
                radio.addEventListener('change', function (e) {
                    // Value с радио-кнопки
                    const selectedType = e.target.value;

                    // Если выбрали "Продолжить с пред. месяца"
                    if (selectedType === 'last_month') {

                        const dateInput = getSelectedDateElement(toSpecialistId);
                        dateInput.disabled = true;

                        generateScheduleUpByLastMonth(toSpecialistId);
                    } else if (selectedType === 'start_by') {
                        // Выбранная дата в календаре
                        const selectedDate = getSelectedDate(toSpecialistId);

                        const dateInput = getSelectedDateElement(toSpecialistId);
                        dateInput.disabled = false;

                        if (selectedDate === '') {
                            new Noty({
                                type: "warning",
                                text: "Пожалуйста, укажите день в календаре"
                            }).show();
                            return;
                        }

                        generateScheduleUpByStartDate(toSpecialistId, selectedDate);
                    }
                });

                // Обработка на изменение календаря
                dateInput.addEventListener('change', function () {

                    // Получение выбранного типа генерации графики кнопки для исполнителя
                    const selectedType = getSelectedRadioForSpecialist(toSpecialistId);

                    // Если выбрали тип "Начать с"
                    if (selectedType !== null && selectedType.value === 'start_by') {

                        // Выбранная дата в календаре
                        const selectedDate = getSelectedDate(toSpecialistId);

                        // Если выбрали дату
                        if (selectedDate !== '') {

                            // Проверяем является ли дата верной. Если график формируем на февраль, то выбор даты должен быть в рамках февраля
                            if (moment(selectedDate).isSame(generateMonth, 'month') === false) {
                                new Noty({
                                    type: "warning",
                                    text: "Неправильно выбран день в календаре. Пожалуйста, выберите день в рамках месяца {{ $month->isoFormat('MMMM') }}"
                                }).show();
                                return;
                            }

                            // Вызываем функцию для генерации доски начиная с выбранной даты
                            generateScheduleUpByStartDate(toSpecialistId, selectedDate);
                        }

                    }
                });
            });

        });

        validateBefore();
        disableCheckboxesByDataDismissal();
        disableCheckboxesBySpecialistDataAdmission();


        /**
         * Функция, которая делает валидацию данных во время отправки формы
         * */
        function validateBefore() {
            // Отправляемая форма
            const form = document.querySelector('.form-create');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Получаем ID специалиста у которого не выбран способ формирования доски
                const result = getUnAssignedCheckboxes();

                // Если не пустой, выводим ошибку
                if (result !== null) {
                    new Noty({
                        type: 'error',
                        text: result.message
                    }).show();
                    return;
                }

                // В ином случае, даем отправить форму
                form.submit();
            })
        }

        /**
         * Функция, которая выделяет корректные чекбоксы в графике работы с соответствием
         * выбранным типом "Продолжить последний месяц"
         * */
        function generateScheduleUpByLastMonth(toSpecialistId) {

            // Специалист для которого генерируем
            const specialist = getToSpecialist(toSpecialistId);

            // Количество рабочих дней
            const specialist_work_day = specialist.count_work_days;

            // Количество выходных дней
            const specialist_weekend_day = specialist.count_weekend_days;

            // Получаем большое значение из режима (из 4/2, получаем 4)
            const maxFromDate = Math.max(specialist_work_day, specialist_weekend_day);

            // Последние дни месяца
            const lastMonthDays = [];

            // Получаем последние дни прошлого месяца и заполняем их в массиве
            for (let i = 0; i < specialist.last_month_schedules.length; i++) {

                // Берем столько дней, сколько у него в режиме прописано
                // Если режим 4 на 2, то из прошлого месяца возьмем 4 дня.
                if (maxFromDate < i) {
                    break;
                }

                lastMonthDays.push({
                    date: moment(specialist.last_month_schedules[i].date).format('YYYY-MM-DD'),
                    work_day: true, // Отмечаем что это рабочий день
                });
            }


            // Заполняем пропуски в графиках, эти пропуски - выходные
            const schedules = insertEssentialWeekends(lastMonthDays);


            ////////

            // Берем последние либо рабочие дни, либо выходные, но не вместе.
            const lastOneTypeDays = [];

            // Проходимся и собираем дни одного типа.
            for (let i = 0; i < schedules.length; i++) {
                // Текущий перебираемый график
                const schedule = schedules[i];

                // Прошлый перебранный график
                const last = schedules[i - 1] ?? null;

                // Если дни различаются по типам, то есть перебираемый день "выходной", а прошлый элемент "рабочий", то останавливаемся
                if (last && last.work_day !== schedule.work_day) {
                    break;
                }

                // В ином случае добавляем в массив
                lastOneTypeDays.push({
                    ...schedule,
                });
            }

            // Смотрим какого типа дни собрали (выходной или рабочий день)
            const lastDay = lastOneTypeDays[0].work_day;

            // Собирает все дни, которые нужно добавить в выводящем результате
            const queue = [];

            // Если все дни - рабочие дни
            if (lastDay === true) {
                // Добавляем дни для корректного начала графика
                // Допустим если график у пользователя 4/4, а у нас в lastOneTypeDays только 2 рабочих дня
                // Мы тогда добавим еще 2. Это нужно чтобы у нас начало формирования было корректное
                for (let i = 0; i < specialist_work_day - lastOneTypeDays.length; i++) {
                    queue.push({
                        work_day: true
                    });
                }

            }

            // Если все дни - выходные дни
            if (lastDay === false) {
                // Добавляем дни для корректного начала графика
                // Допустим если график у пользователя 4/4, а у нас в lastOneTypeDays только 3 выходных дня
                // Мы тогда добавим еще 1. Это нужно чтобы у нас начало формирования было корректное
                for (let i = 0; i < specialist_weekend_day - lastOneTypeDays.length; i++) {
                    queue.push({
                        work_day: false
                    });
                }
            }

            // Формируем период с 01 до 31 для месяца на который график генерируем
            const period = getDatesFromDateRange(
                generateMonth.format('YYYY-MM-DD'),
                generateMonth.clone().endOf('month').format('YYYY-MM-DD'),
                'YYYY-MM-DD'
            );

            // Конец месяца
            const endOfMonth = generateMonth.clone().endOf('month');

            const result = [];

            loop1: for (const day of period) {
                // Заполняем те дни, которые мы собирали выше для корректного формирования
                if (queue.length > 0) {
                    const queue_item = queue.pop();
                    result.push({
                        date: day.format('YYYY-MM-DD'),
                        work_day: queue_item.work_day
                    });
                    continue;
                }


                // Последний элемент
                const lastElement = result[result.length - 1] ?? lastOneTypeDays[0];

                // Если последний день месяца - рабочий
                if (lastElement.work_day === true) {

                    for (let i = 0; i < specialist_weekend_day; i++) {
                        const newLastElement = result[result.length - 1] ?? lastOneTypeDays[0];

                        if (newLastElement.date === endOfMonth.format('YYYY-MM-DD')) {
                            break loop1;
                        }

                        result.push({
                            date: moment(newLastElement.date).add(1, 'day').format('YYYY-MM-DD'),
                            work_day: false,
                        });

                    }
                }


                // Если последний день месяца - выходной
                if (lastElement.work_day === false) {
                    for (let i = 0; i < specialist_work_day; i++) {
                        const newLastElement = result[result.length - 1] ?? lastOneTypeDays[0];

                        if (newLastElement.date === endOfMonth.format('YYYY-MM-DD')) {
                            break loop1;
                        }

                        result.push({
                            date: moment(newLastElement.date).add(1, 'day').format('YYYY-MM-DD'),
                            work_day: true,
                        });
                    }
                }
            }


            fillCheckboxes(toSpecialistId, result);
        }

        /**
         * Функция, которая выделяет корректные чекбоксы в графике работы с соответствием
         * выбранным типом "Начать с"
         * */
        function generateScheduleUpByStartDate(toSpecialistId, selectedDate) {

            // Текущий пользователь
            const specialist = getToSpecialist(toSpecialistId);

            // Количество рабочих дней
            const specialist_work_day = specialist.count_work_days;

            // Количество выходных дней
            const specialist_weekend_day = specialist.count_weekend_days;

            const endOfMonth = generateMonth.clone().endOf('month');

            // Период с выбранной даты и до конца месяца
            const period = getDatesFromDateRange(
                selectedDate,
                endOfMonth.format('YYYY-MM-DD'),
                'YYYY-MM-DD'
            );

            // Результат вывода
            const result = [];

            loop1: for (const day of period) {

                // Последний элемент
                const lastElement = result[result.length - 1] ?? null;

                // Если нет, последнего элемента, то это означает что это первый оборот цикла
                if (lastElement === null) {

                    // Добавление рабочих дней
                    for (let i = 0; i < specialist_work_day; i++) {

                        // Берем первый день
                        const date = day.format('YYYY-MM-DD');

                        // Проверка на то, нужно ли остановить цикл, если день === к концу месяца
                        if (date === endOfMonth.format('YYYY-MM-DD')) {
                            break loop1;
                        }

                        // Сохраняем значение
                        result.push({
                            date: moment(date).add(i, 'day').format('YYYY-MM-DD'),
                            work_day: true,
                        });
                    }
                    continue;
                }

                // Если день в последнем элементе результата - выходной день.
                // То добавляем уже РАБОЧИЕ дни
                if (lastElement.work_day === false) {

                    // Добавление рабочих дней
                    for (let i = 0; i < specialist_work_day; i++) {

                        // Берем последний элемент в result
                        const newLastElement = result[result.length - 1];

                        // Проверка на то, нужно ли остановить цикл, если день === к концу месяца
                        if (newLastElement.date === endOfMonth.format('YYYY-MM-DD')) {
                            break loop1;
                        }

                        // Сохраняем значение
                        result.push({
                            date: moment(newLastElement.date).add(1, 'day').format('YYYY-MM-DD'),
                            work_day: true,
                        });
                    }
                }


                // Если день в последнем элементе результата - рабочий день.
                // То добавляем уже ВЫХОДНЫЕ дни
                if (lastElement.work_day === true) {

                    // Добавление рабочих дней
                    for (let i = 0; i < specialist_weekend_day; i++) {
                        // Последний элемент
                        const newLastElement = result[result.length - 1];

                        // Проверка на то, нужно ли остановить цикл, если день === к концу месяца
                        if (newLastElement.date === endOfMonth.format('YYYY-MM-DD')) {
                            break loop1;
                        }

                        result.push({
                            date: moment(newLastElement.date).add(1, 'day').format('YYYY-MM-DD'),
                            work_day: false,
                        });
                    }
                }
            }

            fillCheckboxes(toSpecialistId, result);
        }

        /**
         * Функция, заполняет выходные в графике работе исполнителя
         *
         * У каждого исполнителя есть график работ (specialist.last_month_schedules).
         * График из последних рабочих дней в прошлом месяце.
         *
         * Пример: мы на текущем странице пытаемся формировать график для февраля, то сюда как schedules параметр
         * придут последние 2-5 дней (количество дней зависит от режима пользователя 2/2,3/3 и т.д) прошлого месяца (января).
         *
         * Пришедшие графики имеют только рабочие дни пользователя. Это функция занимается тем, что мы заполняем в этот график
         * выходные пользователя, так как они в базе не отмечаются.
         *
         * Пример:
         * Приходит в аргументы: [
         *     {date: "2024-01-31"},
         *     {date: "2024-01-30"},
         *     {date: "2024-01-27"},
         *     ...
         * ]
         *
         * Возвращаем:
         * [
         *     {date: "2024-01-31"},
         *     {date: "2024-01-30"},
         *     {date: "2024-01-29"},
         *     {date: "2024-01-28"},
         *     {date: "2024-01-27"},
         *     ...
         * ]
         *
         * Это нужно для корректной отработки алгоритма высчета графика
         * */
        function insertEssentialWeekends(specialistSchedules) {

            const schedules = _.cloneDeep(specialistSchedules);

            // Берем последний день прошлого месяца
            const lastMonthDay = lastMonth.clone().endOf('month');

            // Первый элемент в массиве (самый последний график пользователя)
            const first = schedules[0];
            // Дата этого дня
            const firstDate = moment(first.date);

            // Если последний день прошлого месяца больше, чем последний график который был создан на пользователя
            // Тогда у исполнителя были выходные, добавляем их туда
            // Пример, январь на 31 дней, но schedules вот так:
            // [
            //     {date: "2024-01-30"},
            //     ...
            // ]
            // В таком случае, добавим туда 31 день, отметив как выходной
            if (lastMonthDay.diff(firstDate, 'days') > 0) {
                const period = getDatesFromDateRange(
                    firstDate.clone().add(1, 'day').format('YYYY-MM-DD'),
                    lastMonthDay.clone().format('YYYY-MM-DD'),
                    'YYYY-MM-DD'
                );

                period.forEach((day_item) => {
                    schedules.push({
                        'date': day_item.format('YYYY-MM-DD'),
                        'work_day': false,
                    });
                });
            }

            // Если у нас день последнего графика совпадает с днем последнего дня в месяце и также если у нас графиков больше чем 1,
            // То находим там выходные и заполняем
            if (lastMonthDay.isSame(firstDate, 'day') && schedules.length > 1) {

                // Ищем графики которые разнятся больше чем день
                for (let i = 0; i < schedules.length; i++) {

                    // След. элемент
                    const currentElement = schedules[i];
                    const nextElement = schedules[i + 1] ?? null;

                    // Если след. графика нет, то останавливаемся
                    if (nextElement === null || nextElement === undefined) {
                        break;
                    }

                    const currentElementDate = moment(currentElement.date);
                    const nextElementDate = moment(nextElement.date);


                    // Если разница между графика больше одного дня, то значит там выходной день
                    if (currentElementDate.diff(nextElementDate, 'days') > 1) {
                        const period = getDatesFromDateRange(
                            nextElementDate.clone().add(1, 'day').format('YYYY-MM-DD'),
                            currentElementDate.clone().subtract(1, 'days').format('YYYY-MM-DD'),
                            'YYYY-MM-DD'
                        );

                        period.forEach((day_item) => {
                            schedules.push({
                                'date': day_item.format('YYYY-MM-DD'),
                                'work_day': false,
                            });
                        });
                    }
                }

            }

            // Сортировка по дате
            schedules.sort(function (a, b) {
                // Turn your strings into dates, and then subtract them
                // to get a value that is either negative, positive, or zero.
                return new Date(b.date) - new Date(a.date);
            });

            return schedules;
        }

        /**
         * Метод для заполнения доски (таблица) с чекбоксами.
         *
         * Передается ID пользователя и дни которые с информацией о том, работает ли он в этот день или нет
         * */
        function fillCheckboxes(toSpecialistId, days) {

            // Очистка чекбоксов от старого состояния
            cleanRows(toSpecialistId);

            // Получаем ряд исполнителя
            const userRow = findSpecialistsRow(toSpecialistId);

            // Ряд дней исполнителя
            const scheduleRow = userRow.querySelectorAll('.day');

            // Перебираем каждый день в ряде исполнителя
            scheduleRow.forEach((row) => {
                // Полная дата дня
                const itemDate = row.dataset.date;

                // Перебираем переданные аргументы и проверяем нужно ли отметить день
                days.forEach((day) => {
                    // Если имеется класс disable, то пропускаем заполнение
                    if (row.classList.contains('disabled')) {
                        return;
                    }

                    // Если даты совпадают и это рабочий день, то отмечаем чекбокс
                    if (day.date === itemDate && day.work_day) {
                        row.querySelector('input').checked = true;
                    }

                    // Если даты совпадают и это выходной день, то убираем чекбокс
                    if (day.date === itemDate && day.work_day === false) {
                        row.querySelector('input').checked = false;
                    }
                });
            })
        }

        /**
         * Очищает заполненные чекбоксами ряды
         * */
        function cleanRows(toSpecialistId) {
            const scheduleDiv = document.querySelector('.to-specialist-schedule');

            scheduleDiv.querySelectorAll('tbody tr').forEach(function (tr) {

                // ID исполнителя этого ряда
                const currentRowToSpecialistId = +tr.dataset.specialistId;

                // Если ID пользователей не совпадают, то пропускаем выполнение кода
                if (currentRowToSpecialistId !== +toSpecialistId) {
                    return;
                }

                // Ряд дней исполнителя
                const scheduleRow = tr.querySelectorAll('.day');

                // Очищаем сперва ряд
                scheduleRow.forEach((item) => {
                    item.querySelector('input').checked = false;
                });
            })
        }

        /**
         * Создание массива дней между двумя датами (период)
         * */
        function getDatesFromDateRange(startDate, endDate, format) {
            startDate = moment(startDate, format);
            endDate = moment(endDate, format);

            let dates = [];

            let i = 0;
            while (true) {
                if (startDate.clone().add(i, 'days').diff(endDate, 'days') === 1) {
                    return dates;
                }

                dates.push(startDate.clone().add(i, 'days'));
                i++;
            }
        }

        /**
         * Получение выбранной даты в календаре для конкретного пользователя
         * */
        function getSelectedDate(toSpecialistId) {
            return getSelectedDateElement(toSpecialistId).value;
        }

        /**
         * Получение input который позволяет выбрать дату формирования доски для конкретного пользователя
         * */
        function getSelectedDateElement(toSpecialistId) {
            return document.querySelector(`input[name="toSpecialists[${toSpecialistId}][start_by_date]"]`);
        }

        /**
         * Получаем выбранный тип радио ("продолжить пред.месяц" или "начать с") для пользователя
         * */
        function getSelectedRadioForSpecialist(toSpecialistId) {
            return document.querySelector(`input[name="toSpecialists[${toSpecialistId}][type]"]:checked`);
        }

        /**
         * Получение исполнителя по ID
         * */
        function getToSpecialist(toSpecialistId) {
            for (const key in specialists) {
                // Поиск по ID пользователю
                if (+specialists[key].id === +toSpecialistId) {
                    return specialists[key];
                }
            }
            return null;
        }

        /**
         * Поиск ряда исполнителя в доске графика.
         * Возвращает ссылку на элемент в котором находятся дни исполнителя (01-31)
         * */
        function findSpecialistsRow(specialistId) {

            for (const tr of scheduleDiv.querySelectorAll('tbody tr')) {

                // ID исполнителя этого ряда
                const currentRowToSpecialistId = +tr.dataset.specialistId;

                // Если ID пользователей не совпадают, то пропускаем выполнение кода
                if (currentRowToSpecialistId === +specialistId) {
                    return tr;
                }
            }
        }

        /**
         * Получаем ID пользователя у которого не выбран режим формирования доски.
         *
         * У каждого пользователя есть два опции формирования для него доски:
         * 1. Продолжить прошлый месяц
         * 2. Начать с
         *
         * По умолчанию не выбран способ формирования, клиент должен сам выбрать. Но если клиент не выбрал,
         * то мы должны выдать ошибку.
         *
         * Этот метод занимается тем, что ищет специалиста без выбранной опции и выдает его ID
         * */
        function getUnAssignedCheckboxes() {

            for (const item of manageBlock.querySelectorAll('tr')) {

                // ID обрабатываемого специалиста
                const toSpecialistId = item.dataset.specialistId;

                // Радио-кнопки "Продолжить пред. месяц" и "Начать с"
                const scheduleGenerateTypeRadios = item.querySelectorAll('td input[type="radio"]');

                // Радио "Продолжить предыдущий месяц"
                const startFromLastMonthRadio = scheduleGenerateTypeRadios[0];

                // Радио "Начать с"
                const startByDateRadio = scheduleGenerateTypeRadios[1];

                // Если не выбран ни один режим формирования графика, выдаем ID пользователя
                if (startFromLastMonthRadio.checked === false && startByDateRadio.checked === false) {
                    return {
                        toSpecialistId,
                        message: 'Пожалуйста, выберите способ формирования графика для исполнителя ' + getToSpecialist(toSpecialistId).user.name
                    }
                }

                // Если выбрали "Начать С", но не заполнили календарь, выдаем ID пользователя
                if (startByDateRadio.checked === true && getSelectedDate(toSpecialistId) === "") {
                    return {
                        toSpecialistId,
                        message: 'Пожалуйста, выберите день в календаре для формирования графика для исполнителя ' + getToSpecialist(toSpecialistId).user.name
                    }
                }

                // Если выбрали "Начать С", и заполнили календарь, то тогда проверяем на правильность выбранной даты выдаем ID пользователя
                if (startByDateRadio.checked === true && getSelectedDate(toSpecialistId) !== "") {

                    // Дата, которую указали
                    const selectedDate = getSelectedDate(toSpecialistId);

                    // Проверяем является ли дата верной. Если график формируем на февраль, то выбор даты должен быть в рамках февраля
                    if (moment(selectedDate).isSame(generateMonth, 'month') === false) {
                        return {
                            toSpecialistId,
                            message: "Неправильно выбран день в календаре. Пожалуйста, выберите день в рамках месяца {{ $month->isoFormat('MMMM') }} для исполнителя " + getToSpecialist(toSpecialistId).user.name
                        }
                    }
                }
            }
            return null;
        }

        /**
         * Функция, которая обходит всех исполнителей с date_dismissal.
         *
         * На доске графиков не даем отметить даты, которые выходят за пределы периода "дата увольнения" - ячейки с такими датами делаем неактивными.
         * */
        function disableCheckboxesByDataDismissal() {

            const dismissalSpecialists = specialists.filter((specialist) => specialist.date_dismissal !== null);

            if (dismissalSpecialists.length < 1) {
                return;
            }

            for (const dismissalSpecialist of dismissalSpecialists) {

                // Дата увольнения исполнителя
                const dismissal_date = moment(dismissalSpecialist.date_dismissal);

                // Получаем ряд исполнителя
                const userRow = findSpecialistsRow(dismissalSpecialist.id);

                // Дни в ряде исполнителя
                const days = userRow.querySelectorAll('.day');

                days.forEach((item) => {
                    // Дата в ряде
                    const rowDate = moment(item.dataset.date);

                    // Если дата в ряде, больше чем дата увольнения, то заблокируем для использования
                    if (rowDate.isAfter(dismissal_date)) {
                        item.classList.add('disabled');
                        item.querySelector('input').disabled = true;
                    }
                });
            }

        }

        /**
         * Функция, которая обходит всех исполнителей с date_admission.
         *
         * На доске графиков не даем отметить даты, которые выходят за пределы периода "дата приема" - ячейки с такими датами делаем неактивными.
         * */
        function disableCheckboxesBySpecialistDataAdmission() {
            const admissionSpecialists = specialists.filter((specialist) => specialist.date_admission !== null);

            if (admissionSpecialists.length < 1) {
                return;
            }

            for (const admissionSpecialist of admissionSpecialists) {

                // Дата увольнения исполнителя
                const admissionDate = moment(admissionSpecialist.date_admission);

                // Получаем ряд исполнителя
                const userRow = findSpecialistsRow(admissionSpecialist.id);

                // Дни в ряде исполнителя
                const days = userRow.querySelectorAll('.day');

                days.forEach((item) => {
                    // Дата в ряде
                    const rowDate = moment(item.dataset.date);

                    // Если дата в ряде, меньше чем дата приема, то заблокируем для использования
                    if (rowDate.isBefore(admissionDate)) {
                        item.classList.add('disabled');
                        item.querySelector('input').disabled = true;
                    }
                });
            }

        }
    </script>

@endsection
@loadOnce('packages/moment/min/moment.min.js')
