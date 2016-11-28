@extends('admin.layout')
@section('body')
<h2>イベント情報</h2>
@include('parts.errormessage')
<div class="search_box">
    <div class="search_box_header">
        検索
    </div>
    <div class="search_box_main">
        <form action="{{url('/admin/posts')}}" method="get">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            キーワード
                        </td>
                        <td>
                            <input type="text" name="keyword" placeholder="キーワードを入力してください" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>開催期間</td>
                        <td>
                            <label>
                                <input type="radio" name="event_period" value="all" checked>全期間
                            </label>
                            <label>
                                <input type="radio" name="event_period" value="select">期間選択
                            </label>
                            <div id="event_period_select" style="display: none;">
                                <select name="start_year" class="form-control event_day_form">
                                    @for ($i = 0; $i < 10; $i++)
                                        <option value="{{$today->year - $i}}" @if ($today->year - 1 == $today->year - $i) selected @endif>{{$today->year - $i}}</option>
                                    @endfor
                                </select>年
                                <select class="form-control event_day_form" name="start_month">
                                    @for ($i = 1; $i < 13; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if ($today->month == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>月
                                <select class="form-control event_day_form" name="start_day">
                                    @for ($i = 1; $i < 32; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if ($today->day == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>&nbsp;~&nbsp;
                                <select name="end_year" class="form-control event_day_form">
                                    @for ($i = 0; $i < 10; $i++)
                                        <option value="{{$today->year - $i}}">{{$today->year - $i}}</option>
                                    @endfor
                                </select>年
                                <select class="form-control event_day_form" name="end_month">
                                    @for ($i = 1; $i < 13; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if ($today->month == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>月
                                <select class="form-control event_day_form" name="end_day">
                                    @for ($i = 1; $i < 32; $i++)
                                        <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if ($today->day == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                                    @endfor
                                </select>
                            </div>
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
                <th>撮影地</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div class="admin_pagination">
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
