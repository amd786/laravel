<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="{{{ url('/img/small-logo.png') }}}">
  <title>Power Seal</title>

  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
  {{ Html::style('css/font-awesome.min.css') }}
  {{ Html::style('css/bootstrap.css') }}
  {{ Html::style('css/components.min.css') }}
  {{ Html::style('css/login.min.css') }}
  {{ Html::style('css/style.css') }}
  <!-- END THEME LAYOUT STYLES -->
  {{ Html::script('js/jquery.min.js') }}
  {{ Html::script('js/bootstrap.min.js') }}


  <link rel="shortcut icon" href="favicon.ico" />
  <script type="text/javascript">
  var baseURL = "{!!url('/')!!}";
  </script>
</head>
<body class=" login">
      @yield('content')
</body>
</html>
