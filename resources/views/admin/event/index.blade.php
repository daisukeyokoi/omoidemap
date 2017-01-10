@extends('admin.layout')
@section('body')
<h2>イベント情報</h2>
@include('parts.errormessage')
<div class="search_box">
    <div class="search_box_header">
        検索
    </div>
    <div class="search_box_main">
        <form action="{{url('/admin/event')}}" method="get">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            キーワード
                        </td>
                        <td>
                            <input type="text" name="keyword" placeholder="キーワードを入力してください" class="form-control" value="@if(Input::get('keyword')){{Input::get('keyword')}}@endif">
                        </td>
                    </tr>
                    <tr>
                        <td>開催期間</td>
                        <td>
                            <label>
                                <input type="radio" name="event_period" value="all" @if (Input::get('event_period', 'all') == 'all') checked @endif>全期間
                            </label>
                            <label>
                                <input type="radio" name="event_period" value="select" @if (Input::get('event_period', 'all') == 'select') checked @endif>期間選択
                            </label>
                            <div id="event_period_select" style="display: none;">
                                <div class="">
                                    <label>
                                        <input type="radio" name="event_period_type" value="start" @if (Input::get('event_period_type', 'start') == 'start') checked @endif>イベントスタート日
                                    </label>
                                    <label>
                                        <input type="radio" name="event_period_type" value="end" @if (Input::get('event_period_type', 'start') == 'end') checked @endif>イベント終了日
                                    </label>
                                </div>
                                <select name="start_year" class="form-control event_day_form">
                                    @for ($i = 0; $i < 10; $i++)
                                        <option value="{{$today->year - 5 + $i}}" @if (Input::get('start_year', $today->year - 1) == $today->year - 5 + $i) selected @endif>{{$today->year - 5 + $i}}</option>
                                    @endfor
                                </select>年
                                <select class="form-control event_day_form" name="start_month">
                                    @for ($i = 1; $i < 13; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (Input::get('start_month', $today->month) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>月
                                <select class="form-control event_day_form" name="start_day">
                                    @for ($i = 1; $i < 32; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (Input::get('start_day', $today->day) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>&nbsp;~&nbsp;
                                <select name="end_year" class="form-control event_day_form">
                                    @for ($i = 0; $i < 10; $i++)
                                        <option value="{{$today->year - 5 + $i}}" @if (Input::get('end_year', $today->year) == $today->year - 5 + $i) selected @endif>{{$today->year - 5 + $i}}</option>
                                    @endfor
                                </select>年
                                <select class="form-control event_day_form" name="end_month">
                                    @for ($i = 1; $i < 13; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (Input::get('end_month', $today->month) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>月
                                <select class="form-control event_day_form" name="end_day">
                                    @for ($i = 1; $i < 32; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (Input::get('end_day', $today->day) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>状態</td>
                        <td>
                            <label>
                                <input type="radio" name="state" value="all" @if (Input::get('state', 'all') == 'all') checked @endif>全て
                            </label>
                            <label>
                                <input type="radio" name="state" value="yet" @if (Input::get('state', 'all') == 'yet') checked @endif>未公開
                            </label>
                            <label>
                                <input type="radio" name="state" value="open" @if (Input::get('state', 'all') == 'open') checked @endif>公開中
                            </label>
                            <label>
                                <input type="radio" name="state" value="reveiw" @if (Input::get('state', 'all') == 'review') checked @endif>審査中
                            </label>
                            <label>
                                <input type="radio" name="state" value="close" @if (Input::get('state', 'all') == 'close') checked @endif>終了
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="検索" class="btn btn-primary">
        </form>
    </div>
</div>
<div class="posts_list">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>開催期間</th>
                <th>状態</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td><a href="{{url('/admin/event/detail', $event->id)}}">{{$event->id}}</a></td>
                    <td>{{$event->title}}</td>
                    <td>{{$event->start}}&nbsp;~&nbsp;{{$event->end}}</td>
                    <td>
                        @if ($event->state == 0)
                            未公開
                        @elseif ($event->state == 1)
                            公開
                        @elseif ($event->state == 2)
                            審査中
                        @else
                            終了
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="admin_pagination">
        {!!  $events->appends(Input::get())->render() !!}
    </div>
</div>
@stop
@section('js_partial')
<script>
$(function() {
    $(document).ready(function() {
        if ($("input[name='event_period']:checked").val() == 'select') {
            $('#event_period_select').show();
        }
    });
    $("input[name='event_period']").on('change', function() {
        if ($("input[name='event_period']:checked").val() == 'select') {
            $('#event_period_select').show();
        }else {
            $('#event_period_select').hide();
        }
    });
});
</script>
@stop
