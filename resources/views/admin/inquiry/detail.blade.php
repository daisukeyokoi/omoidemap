@extends('admin.layout')
@section('css_partial')
<style>
.inquiry_detail_content {
    margin-top: 10px;
}
</style>
@stop
@section('body')
@include('parts.errormessage')
<h1>お問い合わせ詳細</h1>
<div class="inquiry_detail_box">
    <p class="inquiry_detail_subject">件名: {{$inquiry->subject}}</p>
    <div class="inquiry_detail_content">
        <div class="inquiry_detail_data">
            <span class="inquiry_detail_email">{{$inquiry->email}}</span>
            <span class="inquiry_detail_date">{{$inquiry->created_at}}</span>
        </div>
        <p>{!! nl2br(htmlspecialchars($inquiry->content)) !!}</p>
    </div>
    @if (count($inquiry->responses) != 0)
        @foreach($inquiry->responses as $response)
            <div class="inquiry_detail_content">
                <div class="inquiry_detail_data">
                    <span class="inquiry_detail_email">OmoideMap運営</span>
                    <span class="inquiry_detail_date">{{$response->created_at}}</span>
                </div>
                <p>{!! nl2br(htmlspecialchars($response->content)) !!}</p>
            </div>
        @endforeach
    @endif
</div>
@if (count($admin_users) != 0)
    <div class="inquiry_response_box">
        <form action="{{url('/admin/inquiry')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="inquiry_id" value="{{$inquiry->id}}">
            <textarea name="response" rows="8" cols="80" class="form-control" placeholder="お問い合わせの返答を入力してください。">{{old('response')}}</textarea>
            <input type="submit" value="返信" class="btn btn-primary" onClick="return confirm_dialog(this, '返信してよろしいですか？');">
            <a href="{{url('/admin/inquiry')}}"><input type="button" class="btn btn-danger" value="戻る"></a>
        </form>
    </div>
@endif
@stop
