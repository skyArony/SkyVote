<?php
include_once '../login/rootConfig.php';
include_once '../../../lib/Activity.class.php';
include_once '../config.php';

session_start();
if (!isset($_SESSION['rootId']) || !isset($_SESSION['username']) || $_SESSION['rootId'] != RootId || $_SESSION['username'] != Username) {
  header("Location: ../login/login.php");
  exit;
}


// 获取数据
$ackey  = $_GET['ackey'];
$activity = new Activity();

$acInfo_json  = $activity->getActivityInfo($ackey);
$acInfo_array = json_decode($acInfo_json, 1)[0];
// 解析数据
$ac_name   = $acInfo_array['activity_name'];
$startTime = date("Y-m-d H:i:s", strtotime($acInfo_array['starttime']));
$endTime   = date("Y-m-d H:i:s", strtotime($acInfo_array['endtime']));
// 候选人数
$candidateNum_json  = $activity->getCandidateNum($ackey);
$candidateNum_array = json_decode($candidateNum_json, 1);
$candidateNum = $candidateNum_array[0]['candidateNum'];

// 获取单个候选人信息
$cankey    = $_GET['cankey'];
$can_json  = $activity->getCandidateInfo($ackey, $cankey);
$can_array = json_decode($can_json, 1) ;
$can_array = $can_array[0];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SkyVote | 修改候选人</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
  <!-- Ionicons -->
  <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.css" rel="stylesheet">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="../../plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="../../plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/flat/blue.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- jQuery 2.2.0 -->
  <script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <!-- lazyload -->
  <script src="../../../VotePages/js/lazyload.min.js"></script>
  <script>
    $(document).ready(function(){
        /*lazyload*/
        $("img.can-img").lazyload({ threshold : 100 ,effect : "fadeIn" , failurelimit : 10});
    });
  </script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">Sk<b>V</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Sky<b>Vote</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['username']; ?> - <?php echo $_SESSION['identity']; ?>
                  <small><?php echo date("Y年m月d日") ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <?php
                if ($_SESSION['rootId'] == RootId) {
                  $html = '<li class="user-body">
                            <div class="row">
                              <div class="col-xs-4 text-center">
                                <a href="#">用户管理</a>
                              </div>
                              <div class="col-xs-4 text-center">
                                <a href="#">活动管理</a>
                              </div>
                              <div class="col-xs-4 text-center">
                                <a href="#">系统设置</a>
                              </div>
                            </div>
                            <!-- /.row -->
                          </li>';
                  echo $html;
                }
              ?>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">修改密码</a>
                </div>
                <div class="pull-right">
                  <a href="../login/loginOut.php" class="btn btn-default btn-flat">注销</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['username']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $_SESSION['identity']; ?></a>
        </div>
      </div>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview active">
          <a href="mailbox.html">
            <i class="fa fa-tasks"></i> <span>活动</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="../activity/activity-view.php">查看</a></li>
            <li><a href="../activity/activity-add.php">新建</a></li>
            <li><a href="#">修改</a></li>
          </ul>
        </li>
          <li class="treeview">
              <a href="#">
                  <i class="fa fa-pie-chart"></i>
                  <span>可视数据</span>
                  <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li><a href="../DataVisualization"><i class="fa fa-circle-o"></i> 查看</a></li>

              </ul>
          </li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $ac_name; ?>
        <small>在此修改活动候选人信息</small>
        <input type="hidden" id="activity-key" value="<?php echo $ackey; ?>"></input>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Candidate</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="candidate-view.php?ackey=<?php echo $ackey; ?>" class="btn btn-primary btn-block margin-bottom">查看候选人</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">导入信息表</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-green"></i> <?php echo $ac_name; ?></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-blue"></i> <?php echo $startTime; ?>至<?php echo $endTime; ?></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> 候选人数<span class="label label-primary pull-right"><?php echo $candidateNum; ?></span></a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->       
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">候选人</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="candidate-view.php?ackey=<?php echo $ackey; ?>"><i class="fa fa-circle-o text-red"></i> 查看候选人</a></li>
                <li><a href="candidate-add.php?ackey=<?php echo $ackey; ?>"><i class="fa fa-circle-o text-yellow"></i> 添加候选人</a></li>
                <li class="active"><a href="#"><i class="fa fa-circle-o text-light-blue"></i> 修改候选人</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- alert -->
        <div class="col-md-9 alert-block" id="alert-warning" style="display: none;">
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> 警告!</h4>
            <p id="warning-value"></p>
          </div>  
        </div>
        <div class="col-md-9 alert-block" id="alert-success" style="display: none;">
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> 成功!</h4>
            <p id="success-value"></p>
          </div>  
        </div>
        <div class="col-md-9 alert-block" id="alert-danger" style="display: none;">
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> 错误!</h4>
            <p id="danger-value"></p>
          </div>  
        </div>
        <!-- /alert -->   
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">修改活动候选人</h3>
            </div>
            <div class="box-body">
              <form role="form">
                <!-- text input -->
                <div class="form-group" id="candidate-name">
                  <label>姓名</label>
                  <input type="text" class="form-control" placeholder="请输入候选人姓名" id="candidate-name-input" max="50" maxlength="50" value="<?php echo $can_array['name']; ?>">
                  <span class="help-block" id="candidate-name-error"></span>
                </div>
                <div class="form-group" id="candidate-contact">
                  <label>联系方式（QQ或TEL）</label>
                  <input type="text" class="form-control" placeholder="请输入联系方式" id="candidate-contact-input" maxlength="50" max="50" value="<?php echo $can_array['contact']; ?>">
                  <span class="help-block" id="candidate-contact-error"></span>
                </div>
                <div class="form-group" id="candidate-key">
                  <label>编号</label>
                  <input type="text" class="form-control" placeholder="请输入编号，不填写则随机" id="candidate-key-input" max="25" maxlength="25" value="<?php echo $can_array['uniquekey']; ?>" disabled>
                  <span class="help-block" id="candidate-key-error"></span>
                </div>
                <!-- textarea -->
                <div class="form-group" id="candidate-intro">
                  <label>简介（500字以内）</label>
                  <textarea class="form-control" rows="3" placeholder="请在此输入简介" id="candidate-intro-input"><?php echo $can_array['introduction']; ?></textarea>
                  <span class="help-block" id="candidate-intro-error"></span>
                </div>
                <div class="form-group">
                  <label>类型</label>
                  <select class="form-control" id="candidate-type">
                    <option>图片</option>
                    <option>视频</option>
                    <option>外链</option>
                    <option>音频</option>
                  </select>
                  <input type="hidden" value="<?php echo $can_array['type']; ?>" id="select-value"></input>
                </div>
                <div class="box-footer candidate-img-footer">
                  <ul class="mailbox-attachments clearfix pull-left">
                    <div class="form-group">
                      <label>参选图片</label>
                    </div>
                    <div id="candidate-img-block">
                    <?php 
                      $imgArray = json_decode($can_array['imgurl'], 1);
                      $i = 0;
                      foreach ($imgArray as $key => $value) {
                        $html = '<li class="candidate-change-img-'.$i.'" style="overflow:hidden;"><div><span class="mailbox-attachment-icon has-img"><img class="can-img" data-original="'.'http://'.Location.'/VoteAdmin/pages/candidate/img/img/'.$value.'" alt="获取错误"></span></div><a href="javascript:removeImg('."'".$value."'".');$('."'".'.candidate-change-img-'.$i."'".').hide(300);" class="upload_delete" title="删除">删除</a><input type="hidden" value="'.$value.'" id="candidate-change-img-'.$i.'"><br /></li>';
                        $i++;
                        echo $html;
                      }
                    ?>
                    </div>                 
                  </ul>
                </div>
                <div class="mailbox-attachment-info candidate-img-footer">
                  <b style="color:#666" id="candidate-img-txt">请选择图片(可多选)</b><br/><br/>
                  <label class="btn btn-default" for="candidate-img-input">添加图片</label>
                  <input type="file" id="candidate-img-input" name="fileselect[]" style="position:absolute;clip:rect(0 0 0 0);" accept="image/*" multiple>
                </div>  
                <div class="box-footer" id="candidate-video-footer">
                  <div class="form-group" id="candidate-video">
                    <label>参选视频</label>
                    <input type="text" class="form-control" placeholder="请把视频上传至腾讯视频，然后复制视频链接粘贴至此" id="candidate-video-input" value="<?php echo $can_array['videourl']; ?>">
                    <span class="help-block" id="candidate-video-error"></span>
                    <li style="list-style: none;display: none;" id="video-content">
                      
                    </li>
                  </div>
                </div>
                <div class="box-footer" id="candidate-link-footer">
                    <div class="form-group" id="candidate-link">
                      <label>外链链接</label>
                      <input type="text" class="form-control" placeholder="请复制外链粘贴链接至此" id="candidate-link-input" value="<?php echo $can_array['linkurl']; ?>">
                      <span class="help-block" id="candidate-link-error"></span>
                    </div>  
                  <ul class="mailbox-attachments clearfix pull-left">
                    <li style="overflow:hidden;">
                      <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display: none;" id="rate-candidate-link">10%</div>
                      <div id="candidate-link-img"><span class="mailbox-attachment-icon has-img"><img src="http://<?php echo Location; ?>/VoteAdmin/pages/candidate/img/cover/<?php echo $can_array['linkcover']; ?>" alt="Attachment"></span></div>
                      <div class="mailbox-attachment-info">
                        <b style="color:#666" id="candidate-link-txt">请选择一张图片作为封面</b><br/><br/>
                        <label class="btn btn-default" for="candidate-linkcover-input">选择图片</label>
                        <input type="file" id="candidate-linkcover-input" name="fileselect[]" style="position:absolute;clip:rect(0 0 0 0);" accept="image/*">
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="box-footer" id="candidate-audio-footer">
                  <ul class="mailbox-attachments clearfix pull-left">
                    <li style="overflow:hidden; width: auto;">
                      <div class="mailbox-attachment-info">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display: none;" id="rate-candidate-audio">10%</div>
                        <div id="candidate-audio"><audio controls="controls"><source src="http://<?php echo Location; ?>/VoteAdmin/pages/candidate/audio/<?php echo $can_array['audiourl']; ?>" type="audio/mp3" ></audio></div>
                        <b style="color:#666" id="candidate-audio-txt">请选择一个mp3文件</b><br/><br/>
                        <label class="btn btn-default" for="candidate-audio-input">选择mp3文件</label>
                        <input type="file" id="candidate-audio-input" accept="audio/mpeg" style="position:absolute;clip:rect(0 0 0 0);">
                      </div>
                    </li>
                  </ul>
                </div>
              </form>
            </div>
            <div class="box-footer">
              <div type="submit" class="btn btn-primary pull-right" id="candidate-change-upload">确认修改</div>
              <button class="btn btn-primary pull-right" id="candidate-files-upload" style="display: none;">提交文件</div>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.3
    </div>
    <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- MyJs -->
<script src="../../bootstrap/js/myjs.js"></script>
<script src="../../bootstrap/js/zxxFile.js"></script>
<script src="../../bootstrap/js/uploadFile.js"></script>
</body>
</html>
