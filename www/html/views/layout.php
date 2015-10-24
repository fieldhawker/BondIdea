<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="jp">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?php if (isset($title)): echo $this->escape($title) . ' - ';endif; ?> KEYWORDS
    </title>

    <!–- jQuery読み込み -–>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!–- BootstrapのJS読み込み -–>
    <script src="/js/bootstrap.min.js"></script>

    <!–- BootstrapのCSS読み込み -–>
    <link href="/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
</head>
<body>

<div 
  class="
        container col-xs-12 col-sm-12 col-md-6 col-lg-6 
        col-xs-offset-0 col-sm-offset-0 col-md-offset-3
        col-lg-offset-3 toppad
        " style="padding:30px 0 0 0">
    <?php echo $_content; ?>
</div>

</body>
</html>
