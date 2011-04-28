<div class="header">
	<div id="top_menu">
		<ul id="menu">
			<li><a id="nav_logo" href="index.php"></a></li>
			<li><div id="nav_space"></div></li>
			<li><a id="nav_home<?php if ($page=="home") echo "_1"; ?>" href="canvas.php?page=home"></a></li>
			<li><a id="nav_mosaic<?php if ($page=="mosaic") echo "_1"; ?>" href="canvas.php?page=mosaic"></a></li>
			<li><a id="nav_search<?php if ($page=="search") echo "_1"; ?>" href="canvas.php?page=search"></a></li>
			<li><a id="nav_contacts<?php if ($page=="contacts") echo "_1"; ?>" href="canvas.php?page=contacts"></a></li>
			<li><a id="nav_lounge<?php if ($page=="lounge") echo "_1"; ?>" href="canvas.php?page=lounge"></a></li>
			<li><a id="nav_mail<?php if ($page=="mail") echo "_1"; ?>" href="canvas.php?page=mail"></a></li>
			<li><a id="nav_trends<?php if ($page=="trends") echo "_1"; ?>" href="canvas.php?page=trends"></a></li>
		</ul>
	</div>
	<div id="sub_menu">
    	<?php session_start(); echo $_SESSION['email'];?> | invite a friend | <a href="canvas.php?page=settings">settings</a> | <a href="../actions/logout.php">logout</a> 
	</div>
</div>