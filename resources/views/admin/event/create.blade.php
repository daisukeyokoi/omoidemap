@extends('admin.layout')
@section('body')
<h2>イベント作成</h2>
@include('parts.errormessage')
<form action="{{url('/admin/event/create')}}" method="post" enctype="multipart/form-data" id="event_form">
    {{csrf_field()}}
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>イメージ画像</td>
                <td>
                    <input class="fileinput file-loading" name="image_file" type="file">
                </td>
            </tr>
            <tr>
                <td>タイトル</td>
                <td>
                    <input type="text" name="title" placeholder="タイトルを入力してください(20文字以内)" class="form-control" value="{{old('title')}}" maxlength="20">
                </td>
            </tr>
            <tr>
                <td>説明文</td>
                <td>
                    <textarea name="description" rows="8" cols="40" placeholder="説明文を入力してください" class="form-control">{{old('description')}}</textarea>
                </td>
            </tr>
            <tr>
                <td>開催期間</td>
                <td>
                    <select name="start_year" class="form-control event_day_form">
                        @for ($i = 0; $i < 10; $i++)
                            <option value="{{$today->year + $i}}" @if (old('start_year') == $today->year + $i) selected @endif>{{$today->year + $i}}</option>
                        @endfor
                    </select>年
                    <select class="form-control event_day_form" name="start_month">
                        @for ($i = 1; $i < 13; $i++)
                            <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if(old('start_month', $today->month) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                        @endfor
                    </select>月
                    <select class="form-control event_day_form" name="start_day">
                        @for ($i = 1; $i < 32; $i++)
                            <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (old('start_day', $today->day) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                        @endfor
                    </select>&nbsp;~&nbsp;
                    <select name="end_year" class="form-control event_day_form">
                        @for ($i = 0; $i < 10; $i++)
                            <option value="{{$today->year + $i}}" @if (old('end_year') == $today->year + $i) selected @endif>{{$today->year + $i}}</option>
                        @endfor
                    </select>年
                    <select class="form-control event_day_form" name="end_month">
                        @for ($i = 1; $i < 13; $i++)
                            <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (old('end_month', $today->month) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                        @endfor
                    </select>月
                    <select class="form-control event_day_form" name="end_day">
                        @for ($i = 1; $i < 32; $i++)
                            <option value="{{str_pad($i, 2, 0, STR_PAD_LEFT)}}" @if (old('end_day', $today->day) == str_pad($i, 2, 0, STR_PAD_LEFT)) selected @endif>{{str_pad($i, 2, 0, STR_PAD_LEFT)}}</option>
                        @endfor
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="btn_center">
        <input type="submit" value="イベント作成" class="btn btn-primary">
    </div>
</form>
@stop
@section('css_partial')
<link rel="stylesheet" href="{{url('/css/lib/fileinput/4.3.1/fileinput.min.css')}}">
@stop
@section('js_partial')
<script src="{{url('/js/lib/fileinput/4.3.1/fileinput.min.js')}}"></script>
<script src="{{url('/js/lib/fileinput/4.3.1/fileinput_locale_ja.js')}}"></script>
<script>
(function($) {
  $(".fileinput").fileinput({
    "language": "ja",
    "showUpload": false,
    "showCaption": false
  });
}(jQuery));
$(function() {
    $(document).ready(function() {
        createStartForm();
        createEndForm();
    });
    $("[name=start_year], [name='start_month'], [name='start_day']").on('change', function() {
        $("[name='start']").remove();
        createStartForm();
    });
    $("[name=end_year], [name='end_month'], [name='end_day']").on('change', function() {
        $("[name='end']").remove();
        createEndForm();
    });

    function createStartForm() {
        var start = String($("[name='start_year']").val()) + '-' + String($("[name='start_month']").val()) + '-' + String($("[name='start_day']").val());
        var input = $(document.createElement('input'));
        input.attr('type', 'hidden');
        input.attr('name', 'start');
        input.val(start);
        $("#event_form").append(input);
    }

    function createEndForm() {
        var end = String($("[name='end_year']").val()) + '-' + String($("[name='end_month']").val()) + '-' + String($("[name='end_day']").val());
        var input = $(document.createElement('input'));
        input.attr('type', 'hidden');
        input.attr('name', 'end');
        input.val(end);
        $("#event_form").append(input);
    }
});
</script>
@stop
