<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>登录</title>
    <link rel="icon" href="<?php echo base_url('favicon.ico') ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dist/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dist/css/normalize.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dist/css/reglog.css') ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url('dist/js/jquery-1.10.2.min.js') ?>"></script>
  </head>
<body class="eternity-form">
<section class="colorBg1 colorBg">
    <div class="container">
            <div class="login-form-section">
                <div class="login-content  animated bounceIn">
                    <?php 
                      $attr = array('role' => 'form');
                      echo form_open('login', $attr);
                    ?>
					<div class="section-title">
						<h3>登录你的账号</h3>
						<?php echo validation_errors(); ?>
					</div>
					<div class="textbox-wrap focused" id='div_user'>
						<div class="input-group">
							<span class="input-group-addon "><i class="icon-user" style="color: #ff5f83"></i></span>
							<input onfocus="focususer()" onblur="bluruser()" type="text" class="form-control" name="username" autocomplete="off" value="<?php echo set_value('username'); ?>" onpaste="return false" oncontextmenu="return false" required="true" maxlength="12" placeholder="用户名" autofocus="true">
						</div>
					</div>
					<div class="textbox-wrap" id='div_pwd'>
						<div class="input-group">
							<span class="input-group-addon "><i class="icon-key" style="color: #ffff00"></i></span>
							<input onfocus="focusPswd()" onblur="blurPswd()" type="password" class="form-control" name="password" autocomplete="off" onpaste="return false" oncontextmenu="return false" placeholder="密码" required="true">
						</div>
					</div>
					<div class="login-form-action clearfix">
						<a href="<?php echo site_url().'register' ?>" class="btn btn-success pull-left blue-btn">注册</a>                          
						<button type="submit" class="btn btn-success pull-right green-btn">登录</button>
					</div>
				</form>
			</div>


		</div>
	</div>
</section>

    <script type="text/javascript">
		//user项获得焦点的时候：
		function focususer(){
			var obj = document.getElementById("div_user");
				obj.className = "textbox-wrap focused";

		}

		//user项失去焦点的时候：
		function bluruser(){
			var obj = document.getElementById("div_user");
				obj.className = "textbox-wrap";
		}

		//密码项获得焦点的时候：
		function focusPswd(){
			var obj = document.getElementById("div_pwd");
				obj.className = "textbox-wrap focused";

		}

		//密码项失去焦点的时候：
		function blurPswd(){
			var obj = document.getElementById("div_pwd");
				obj.className = "textbox-wrap";
		}
    </script>
</body>
</html>