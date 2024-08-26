<div id="timeline">
    @foreach(array_reverse($revisions) as $revisionDate => $dateRevisions)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ Carbon\Carbon::parse($revisionDate)->isoFormat(config('backpack.ui.default_date_format')) }}</h5>
            </div>
            <ul class="list-group list-group-flush" style="width: 100%">
                @foreach(array_reverse($dateRevisions) as $key => $history)
                    @if($history->key == 'created_at' && !$history->old_value)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1">
                                    {{ $history->userResponsible()?$history->userResponsible()->name .' '. $history->userResponsible()->surname:trans('revise-operation::revise.guest_user') }}
                                    {{ trans('revise-operation::revise.created_this') }}
                                    <strong>{{ $crud->entity_name }}</strong>
                                    <span
                                        class="badge badge-secondary bg-secondary">#{{$history->revisionable_id }}</span>
                                </h5>
                                <small class="text-muted">
                                    <i class="la la-clock"></i>
                                    {{ Carbon\Carbon::parse($history->created_at)->isoFormat(config('backpack.ui.default_datetime_format')) }}
                                </small>
                            </div>
                        </li>
                    @else
                        @if($key == 0 || (((array_reverse($dateRevisions))[$key-1]->created_at != $history->created_at || array_reverse($dateRevisions)[$key-1]->user_id != $history->user_id)))
                            <li class="list-group-item">
                                <div class="d-flex  justify-content-between">
                                    <small class="text-muted">
                                        {{ trans('revise-operation::revise.changed_the') }}
                                        {{ $history->userResponsible()?$history->userResponsible()->name .' '. $history->userResponsible()->surname:trans('revise-operation::revise.guest_user') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="la la-clock"></i>
                                        {{ Carbon\Carbon::parse($history->created_at)->isoFormat(config('backpack.ui.default_datetime_format')) }}
                                    </small>
                                </div>
                                <ul style="margin-bottom: 0;">
                                    @endif
                                    <li class="mb-1">
                                        Параметр <b>{{ $history->fieldName() }}</b>
                                        изменился с <span
                                            class="badge badge-secondary">{{ $history->oldValue() ?? '-' }}</span>
                                        на <span class="badge badge-success">{{ $history->newValue() ?? '-'}}</span>
                                    </li>
                                    @if( !isset($dateRevisions[$key+1]) || ((array_reverse($dateRevisions))[$key+1]->created_at != $history->created_at || array_reverse($dateRevisions)[$key+1]->user_id != $history->user_id))
                                </ul>
                            </li>
                        @endif
                    @endif

                @endforeach
            </ul>
        </div>
    @endforeach
</div>
