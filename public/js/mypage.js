$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});

// 選択中のaタグを無効に
$(function(){
  $('a.selected').click(function(){
    return false;
  })
});

// マイページのプロフィール画像のマウス処理
$('#image').mouseover(function() {
  $('#setting-image').show();
});
$('#image').mouseout(function() {
  $('#setting-image').hide();
});

// プロフィール編集ページのリストの背景色変更
$('.setting li').mouseover(function(){
  if(!$(this).hasClass("selected")){
    $(this).addClass("mouseovered");
  }
});
$('.setting li').mouseout(function() {
  $(this).removeClass("mouseovered");
});

// プロフィール画像選択時の変更ボタン表示
function changeImage() {
  $('.mypage_list .profile-content .profile-main .content .img_form #submitBtn').show();
}

// 左上のプロフィール画像から画像変更（未完成）
// $('#file').change(function(e){
//   url = "http://" + location.host + "/mypage/ajax/updateprofile";
//   var file = e.target.files[0];
//   var img = new Image();
//   var reader = new FileReader();
//   reader.onload = function(event){
//     img.onload = function(){
//       console.log("a");
//       var data = {data:img.src.split(',')[1]};
//       console.log(data);
//       $.ajax({
//         type: "POST",
//         url: url,
//         data: reader.result,
//         success: function(res){
//           console.log(res.message);
//         }
//       });
//     }
//   }
//   reader.readAsDataURL(file);
// });
