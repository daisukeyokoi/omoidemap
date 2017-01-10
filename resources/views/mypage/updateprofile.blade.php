@extends('mypageLayout')

@section('mypage_content')
@include('parts.errormessage')
<div class="profile-content">
	
	<div class="side">
		<ul class="setting">
			<a href="#">
				<li class="profile selected">
					<i class="fa fa-user fa-lg" aria-hidden="true"></i>プロフィール
				</li>
			</a>
			<a href="{{url('/mypage/updateprivacy')}}">
				<li class="privacy">
					<i class="fa fa-lock fa-lg" aria-hidden="true"></i>プライバシー
				</li>
			</a>
		</ul>
	</div>
	
	<div class="profile-main selected" id="profile">
		<div class="title">
			<i class="fa fa-user fa-lg" aria-hidden="true"></i>プロフィール設定
		</div>
		<table class="content">
			<tbody>
				<tr>
					<th>
						<img src="{{url('/show/user', $user->id)}}">
					</th>
					<td>
						<form method="POST" action="{{url('/mypage/updateprofile/updateImage')}}" enctype="multipart/form-data" class="img_form">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="file" id="file" name="file">
							<input type="submit" class="btn btn-success" id="submitBtn" value="画像を変更">
						</form>
					</td>
				</tr>
				<form method="POST" action="{{url('/mypage/updateprofile/update')}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<tr>
						<th>
							<label for="name">ニックネーム</label>
						</th>
						<td>
							<input type="text" name="nickname" value="@if (old('nickname')){{old('nickname')}}@else{{$user->nickname}}@endif">
						</td>
					</tr>
					<tr>
						<th>
							<label for="">生年月日</label>
						</th>
						<td>
							<div>
					            <select name="year">
					            	@for($i = 1900; $i < 2017; $i++)
					            		<option value={{$i}} @if (old('year', $user->birth_year) == $i) selected @endif>{{$i}}</option>
					            	@endfor
					            </select>&nbsp;年&nbsp;
					            <select name="month">
					            	@for($i = 1; $i < 13; $i++)
					            		<option value={{$i}} @if (old('month', $user->birth_month) == $i) selected @endif>{{$i}}</option>
					            	@endfor
					            </select>&nbsp;月&nbsp;
					            <select name="day">
					            	@for($i = 1; $i < 32; $i++)
					            		<option value={{$i}} @if (old('day', $user->birth_day) == $i) selected @endif>{{$i}}</option>
					            	@endfor
					            </select>&nbsp;日&nbsp;
					        </div>
						</td>
					</tr>
					<tr>
						<th>
							<label for="sex">性別</label>
						</th>
						<td>
							<select name="sex">
								<option value="1" @if (old('sex', $user->sex) == 1) selected @endif>男性</option>
								<option value="2" @if (old('sex', $user->sex) == 2) selected @endif>女性</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<label for="birthplace">出身地</label>
						</th>
						<td>
							<select name="birthplace">
								@foreach($prefectures as $prefecture)
									<option value="{{$prefecture->id}}" @if (old('birthplace', $user->birthplace) == $prefecture->id) selected @endif>{{$prefecture->name}}</option>
								@endforeach
							</select>
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
