<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>思い出MAP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="{{url('/css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
    <link rel="stylesheet" href="{{url('/css/vex.css')}}" />
    <link rel="stylesheet" href="{{url('/css/vex-theme-os.css')}}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    @yield('css_partial')
  </head>
  <body>
    <div class="wrapper">
      @include('parts.header')
      @yield('body')
      @include('parts.footer')
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
    <script src="{{url('/js/vex.combined.min.js')}}"></script>
    <script>vex.defaultOptions.className = 'vex-theme-os';</script>
    <script>
      function confirm_dialog(self, msg) {
          var form = $(self).closest('form');
          vex.dialog.confirm({
              message: msg,
              callback: function(value) {
                  if (value) {
                      form.off('submit');
                      form.submit();
                  }
              }
          });
          return false;
      }
    </script>
    @yield('js_partial')
  </body>
</html>
