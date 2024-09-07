{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Авторы" :link="backpack_url('author')" />
<x-backpack::menu-item title="Коран" :link="backpack_url('quran')" />
<x-backpack::menu-item title="Нашиды" :link="backpack_url('nashid')" />
<x-backpack::menu-item title="Азкары" :link="backpack_url('azkar')" />
<x-backpack::menu-item title="Типы азкаров" :link="backpack_url('azkar-type')" />

