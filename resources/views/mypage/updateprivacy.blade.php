@extends('mypageLayout')

@section('mypage_content')
@include('parts.errormessage')
<div class="profile-content">

	<div class="profile-side">
		<ul class="setting">
			<a href="{{url('/mypage/updateprofile')}}">
				<li class="profile">
					<i class="fa fa-user fa-lg" aria-hidden="true"></i>プロフィール
				</li>
			</a>
			<a href="#">
				<li class="privacy selected">
					<i class="fa fa-lock fa-lg" aria-hidden="true"></i>プライバシー
				</li>
			</a>
		</ul>
	</div>

	<div class="profile-main selected" id="privacy">
		<div class="title">
			<i class="fa fa-lock fa-lg" aria-hidden="true"></i>プライバシー設定
		</div>
		<select class='select_title' onchange="location.href=value">
			<option value="/mypage/updateprofile"><i class="fa fa-user fa-lg" aria-hidden="true"></i>プロフィール</option>
			<option value="/mypage/updateprivacy" selected="selected"><i class="fa fa-user fa-lg" aria-hidden="true"></i>プライバシー</option>
		</select>
		<table class="content">
			<div class="sub_title">
				<i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i>メールアドレス
			</div>
			<tbody>
				<form method="POST" action="{{url('/mypage/updateprofile/updateEmail')}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<tr>
						<th>
							<label for="email">メールアドレス</label>
						</th>
						<td>
							{{$user->email}}
						</td>
					</tr>
					<tr>
						<th>
							<label for="email">新しいメールアドレス</label>
						</th>
						<td>
							<input type="text" name="email">
						</td>
					</tr>
					<tr>
						<th>
							<label for="email">新しいメールアドレス（確認）</label>
						</th>
						<td>
							<input type="text" name="email_confirmation">
						</td>
					</tr>
					<tr class="button-box">
						<th>
						</th>
						<td>
							<input type="submit" class="btn btn-success" id="submitBtn" value="変更を保存">
						</td>
					</tr>
				</form>
			</tbody>
		</table>

		<table class="content">
			<div class="sub_title">
				<i class="fa fa-key fa-lg" aria-hidden="true"></i>パスワード
			</div>
			<tbody>
				<form method="POST" action="{{url('/mypage/updateprofile/updatePassword')}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<tr>
						<th>
							<label for="email">新しいパスワード</label>
						</th>
						<td>
							<input type="password" name="password">
						</td>
					</tr>
					<tr>
						<th>
							<label for="email">新しいパスワード（確認）</label>
						</th>
						<td>
							<input type="password" name="password_confirmation">
						</td>
					</tr>
					<tr class="button-box">
						<th>
						</th>
						<td>
							<input type="submit" class="btn btn-success" id="submitBtn" value="変更を保存">
						</td>
					</tr>
				</form>
			</tbody>
		</table>
	</div>

</div>
@endsection
