@extends("layout")
<!-- layout.blade.phpのデータを使用するという意味？ -->
@section("body")
<!-- layoutの@yield('body')に記述するという意味 -->
<h1 style="margin-top:100px;">{{Input::get("title", "申し訳ありません")}}</h1>
@if(Input::get("title", "bb") == "運営者情報")
  @include("parts.footer.admininfo")

@elseif(Input::get("title", "bb") == "免責条項")
  <P>
    免責条項です。
  </P>
@else
  <P>
    ただいまコンテンツ作成中です。
  </P>

@endif
<!-- endifと最後に書く決まり -->
@stop
<!-- extendsとセット？ -->
