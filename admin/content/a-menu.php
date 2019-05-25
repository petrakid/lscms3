
<header id="header" class="page-topbar">
<div class="navbar-fixed">
<nav class="navbar-color gradient-45deg-light-blue-cyan">
<div class="nav-wrapper">
<ul class="left">
<li>
<h1 class="logo-wrapper">
<a href="<?php echo $g['site_url'] ?>/admin/dashboard/" class="brand-logo darken-1">
<img src="<?php echo $g['site_url'] ?>/content/assets/img/logo/ls-logo.png" alt="Luthersites Logo" />
</a>
</h1>
</li>
</ul>

<div class="header-search-wrapper hide-on-med-and-down">
<i class="material-icons">search</i>
<input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Admin" />
</div>

<ul class="right hide-on-med-and-down">
<li>
<a href="<?php echo $g['site_url'] ?>" class="waves-effect waves-block waves-light">
<i class="material-icons">keyboard_return</i>
</a>
</li>
<li>
<a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen">
<i class="material-icons">settings_overscan</i>
</a>
</li>
<li>
<a href="javascript:void(0);" class="waves-effect waves-block waves-light notification-button" data-activates="notifications-dropdown">
<i class="material-icons">notifications_none
<small class="notification-badge pink accent-2">5</small>
</i>
</a>
</li>
<li>
<a href="javascript:void(0);" class="waves-effect waves-block waves-light profile-button" data-activates="profile-dropdown">
<span class="avatar-status avatar-online">
<img src="<?php echo $g['site_url'] ?>/content/assets/img/avatar/<?php echo $_SESSION['user']['user_avatar'] ?>" alt="avatar" />
<i></i>
</span>
</a>
</li>
</ul>
            
<!-- notifications-dropdown (not sure if keeping) -->
<ul id="notifications-dropdown" class="dropdown-content">
<li>
<h6>NOTIFICATIONS
<span class="new badge">5</span>
</h6>
</li>
<li class="divider"></li>
<li>
<a href="#!" class="grey-text text-darken-2">
<span class="material-icons icon-bg-circle cyan small">add_shopping_cart</span> A new order has been placed!</a>
<time class="media-meta" datetime="2015-06-12T20:50:48+08:00">2 hours ago</time>
</li>
<li>
<a href="#!" class="grey-text text-darken-2">
<span class="material-icons icon-bg-circle red small">stars</span> Completed the task</a>
<time class="media-meta" datetime="2015-06-12T20:50:48+08:00">3 days ago</time>
</li>
<li>
<a href="#!" class="grey-text text-darken-2">
<span class="material-icons icon-bg-circle teal small">settings</span> Settings updated</a>
<time class="media-meta" datetime="2015-06-12T20:50:48+08:00">4 days ago</time>
</li>
<li>
<a href="#!" class="grey-text text-darken-2">
<span class="material-icons icon-bg-circle deep-orange small">today</span> Director meeting started</a>
<time class="media-meta" datetime="2015-06-12T20:50:48+08:00">6 days ago</time>
</li>
<li>
<a href="#!" class="grey-text text-darken-2">
<span class="material-icons icon-bg-circle amber small">trending_up</span> Generate monthly report</a>
<time class="media-meta" datetime="2015-06-12T20:50:48+08:00">1 week ago</time>
</li>
</ul>

<ul id="profile-dropdown" class="dropdown-content">
<li>
<a href="<?php echo $g['site_url'] ?>/admin/profile/" class="grey-text text-darken-1">
<i class="material-icons">face</i> Profile</a>
</li>
<li>
<a href="<?php echo $g['site_url'] ?>/admin/help/" class="grey-text text-darken-1">
<i class="material-icons">live_help</i> Help</a>
</li>
<li class="divider"></li>
<li>
<a href="<?php echo $g['site_url'] ?>/admin/logout/" class="grey-text text-darken-1">
<i class="material-icons">keyboard_tab</i> Logout</a>
</li>
</ul>
</div>
</nav>
</div>
</header>

<div id="main">
<div class="wrapper">

<?php
if(isset($_GET['f']) && $_GET['f'] == '') {
     ?>
     <aside id="left-sidebar-nav">
     <ul id="slide-out" class="side-nav fixed leftside-navigation">
     <li class="user-details cyan darken-2">
     <div class="row">
     <div class="col col s4 m4 l4">
     <img src="<?php echo $g['site_url'] ?>/content/assets/img/avatar/<?php echo $_SESSION['user']['user_avatar'] ?>" alt="" class="circle responsive-img valign profile-image cyan" />
     </div>
     
     <div class="col col s8 m8 l8">
     <ul id="profile-dropdown-nav" class="dropdown-content">
     <li>
     <a href="<?php echo $g['site_url'] ?>/admin/profile/" class="grey-text text-darken-1">
     <i class="material-icons">face</i> Profile</a>
     </li>
     <li>
     <a href="<?php echo $g['site_url'] ?>/admin/help/" class="grey-text text-darken-1">
     <i class="material-icons">live_help</i> Help</a>
     </li>
     <li class="divider"></li>
     <li>
     <a href="<?php echo $g['site_url'] ?>/admin/logout/" class="grey-text text-darken-1">
     <i class="material-icons">keyboard_tab</i> Logout</a>
     </li>
     </ul>
     
     <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown-nav"><?php echo $_SESSION['user']['first_name'] .' '. $_SESSION['user']['last_name'] ?><i class="mdi-navigation-arrow-drop-down right"></i></a>
     <p class="user-roal">
     <?php
     switch($_SESSION['user']['security_level']) {
          case 1:
          case 5:
          case 10:
          case 50:
               echo 'Administrator';
          break;
          case 90:
          case 99:
               echo 'Superadmin';
          break;
          default:
               echo 'Not Defined';
          break;
     }
     ?>
     </p>
     </div>
     </div>
     </li>
     
     <li class="no-padding">
     <ul class="collapsible" data-collapsible="accordion">
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/dashboard/" class="waves-effect waves-cyan">
     <i class="material-icons">dashboard</i>
     <span class="nav-text">Dashboard</span>
     </a>
     </li>
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/pages/" class="waves-effect waves-cyan">
     <i class="material-icons">format_list_bulleted</i>
     <span class="nav-text">Pages</span>
     </a>
     </li>
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/menus/" class="waves-effect waves-cyan">
     <i class="material-icons">menu</i>
     <span class="nav-text">Menus</span>
     </a>
     </li>
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/blocks/" class="waves-effect waves-cyan">
     <i class="material-icons">view_module</i>
     <span class="nav-text">Blocks</span>
     </a>
     </li>
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/features/" class="waves-effect waves-cyan">
     <i class="material-icons">featured_video</i>
     <span class="nav-text">Features</span>
     </a>
     </li>     
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/style/" class="waves-effect waves-cyan">
     <i class="material-icons">format_paint</i>
     <span class="nav-text">Style</span>
     </a>
     </li>
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/settings/" class="waves-effect waves-cyan">
     <i class="material-icons">settings</i>
     <span class="nav-text">Settings</span>
     </a>
     </li>
     <li class="bold">
     <a href="<?php echo $g['site_url'] ?>/admin/users/" class="waves-effect waves-cyan">
     <i class="material-icons">people</i>
     <span class="nav-text">Users</span>
     </a>
     </li>
     </ul>
     </li>
     </ul>
     <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only">
     <i class="material-icons">menu</i>
     </a>
     </aside>
     <?php
}
?>

