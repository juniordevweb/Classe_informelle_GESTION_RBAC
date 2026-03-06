
<?= $this->extend('templates/index') ?>
<?= $this->section('content') ?>
           <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="pull-left page-title">Bienvenue !</h3>
                                <ol class="breadcrumb pull-right">
                                    <li><a href="#">Moltran</a></li>
                                    <li class="active">Dashboard</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Start Widget -->
                        <!--Widget-4 -->
                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-info"><i class="ion-social-usd"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="counter text-dark">15852</span>
                                        Total Sales
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase">Target <span class="pull-right">60%</span></h5>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-purple"><i class="ion-ios7-cart"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="counter text-dark">956</span>
                                        New Orders
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase">Target <span class="pull-right">90%</span></h5>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-purple" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
                                                    <span class="sr-only">90% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-success"><i class="ion-android-contacts"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="counter text-dark">5210</span>
                                        New Users
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase">Target <span class="pull-right">57%</span></h5>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: 57%">
                                                    <span class="sr-only">57% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-primary"><i class="ion-eye"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="counter text-dark">20544</span>
                                        Unique Visitors
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <h5 class="text-uppercase">Target <span class="pull-right">60%</span></h5>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End row-->


                        <div class="row">
                            <div class="col-xl-8">
                                <div class="portlet"><!-- /portlet heading -->
                                    <div class="portlet-heading">
                                        <h3 class="portlet-title text-dark text-uppercase">
                                            Website Stats
                                        </h3>
                                        <div class="portlet-widgets">
                                            <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                            <span class="divider"></span>
                                            <a data-toggle="collapse" href="#portlet1"><i class="ion-minus-round"></i></a>
                                            <span class="divider"></span>
                                            <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="portlet1" class="panel-collapse collapse show">
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="website-stats" style="position: relative;height: 320px"></div>
                                                    <div class="row text-center m-t-30">
                                                        <div class="col-sm-4">
                                                            <h4 class="counter">86,956</h4>
                                                            <small class="text-muted"> Weekly Report</small>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <h4 class="counter">86,69</h4>
                                                            <small class="text-muted">Monthly Report</small>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <h4 class="counter">948,16</h4>
                                                            <small class="text-muted">Yearly Report</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /Portlet -->
                            </div> <!-- end col -->

                            <div class="col-xl-4">
                                <div class="portlet"><!-- /portlet heading -->
                                    <div class="portlet-heading">
                                        <h3 class="portlet-title text-dark text-uppercase">
                                            Website Stats
                                        </h3>
                                        <div class="portlet-widgets">
                                            <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                            <span class="divider"></span>
                                            <a data-toggle="collapse" href="#portlet2"><i class="ion-minus-round"></i></a>
                                            <span class="divider"></span>
                                            <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="portlet2" class="panel-collapse collapse show">
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="pie-chart">
                                                        <div id="pie-chart-container" class="flot-chart" style="height: 320px">
                                                        </div>
                                                    </div>

                                                    <div class="row text-center m-t-30">
                                                        <div class="col-sm-6">
                                                            <h4 class="counter">86,956</h4>
                                                            <small class="text-muted"> Weekly Report</small>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h4 class="counter">86,69</h4>
                                                            <small class="text-muted">Monthly Report</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /Portlet -->
                            </div> <!-- end col -->
                        </div> <!-- End row -->


                        <div class="row">
                            <!-- INBOX -->
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Inbox</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="inbox-widget nicescroll mx-box">
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-1.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Chadengle</p>
                                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                                    <p class="inbox-item-date">13:40 PM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-2.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Tomaslau</p>
                                                    <p class="inbox-item-text">I've finished it! See you so...</p>
                                                    <p class="inbox-item-date">13:34 PM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-3.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Stillnotdavid</p>
                                                    <p class="inbox-item-text">This theme is awesome!</p>
                                                    <p class="inbox-item-date">13:17 PM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-4.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Kurafire</p>
                                                    <p class="inbox-item-text">Nice to meet you</p>
                                                    <p class="inbox-item-date">12:20 PM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-5.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Shahedk</p>
                                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                                    <p class="inbox-item-date">10:15 AM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-6.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Adhamdannaway</p>
                                                    <p class="inbox-item-text">This theme is awesome!</p>
                                                    <p class="inbox-item-date">9:56 AM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-8.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Arashasghari</p>
                                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                                    <p class="inbox-item-date">10:15 AM</p>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-9.jpg" class="rounded-circle" alt=""></div>
                                                    <p class="inbox-item-author">Joshaustin</p>
                                                    <p class="inbox-item-text">I've finished it! See you so...</p>
                                                    <p class="inbox-item-date">9:56 AM</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <!-- CHAT -->
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header"> 
                                        <h3 class="card-title">Chat</h3> 
                                    </div> 
                                    <div class="card-body"> 
                                        <div class="chat-conversation">
                                            <ul class="conversation-list nicescroll">
                                                <li class="clearfix">
                                                    <div class="chat-avatar">
                                                        <img src="assets/images/avatar-1.jpg" alt="male">
                                                        <i>10:00</i>
                                                    </div>
                                                    <div class="conversation-text">
                                                        <div class="ctext-wrap">
                                                            <i>John Deo</i>
                                                            <p>
                                                                Hello!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="clearfix odd">
                                                    <div class="chat-avatar">
                                                        <img src="assets/images/users/avatar-5.jpg" alt="Female">
                                                        <i>10:01</i>
                                                    </div>
                                                    <div class="conversation-text">
                                                        <div class="ctext-wrap">
                                                            <i>Smith</i>
                                                            <p>
                                                                Hi, How are you? What about our next meeting?
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="chat-avatar">
                                                        <img src="assets/images/avatar-1.jpg" alt="male">
                                                        <i>10:01</i>
                                                    </div>
                                                    <div class="conversation-text">
                                                        <div class="ctext-wrap">
                                                            <i>John Deo</i>
                                                            <p>
                                                                Yeah everything is fine
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="clearfix odd">
                                                    <div class="chat-avatar">
                                                        <img src="assets/images/users/avatar-5.jpg" alt="male">
                                                        <i>10:02</i>
                                                    </div>
                                                    <div class="conversation-text">
                                                        <div class="ctext-wrap">
                                                            <i>Smith</i>
                                                            <p>
                                                                Wow that's great
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="row">
                                                <div class="col-sm-9 chat-inputbar">
                                                    <input type="text" class="form-control chat-input" placeholder="Enter your text">
                                                </div>
                                                <div class="col-sm-3 chat-send">
                                                    <button type="submit" class="btn btn-info btn-block waves-effect waves-light">Send</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div> <!-- end col-->


                            <!-- TODOs -->
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header"> 
                                        <h3 class="m-0 card-title">Todo</h3> 
                                    </div> 
                                    <div class="card-body todoapp"> 
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h4 id="todo-message"><span id="todo-remaining"></span> of <span id="todo-total"></span> remaining</h4> 
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="" class="pull-right btn btn-primary btn-sm waves-effect waves-light" id="btn-archive">Archive</a>
                                            </div>
                                        </div>

                                        <ul class="list-group no-margn nicescroll todo-list" style="max-height: 288px" id="todo-list"></ul>

                                         <form name="todo-form" id="todo-form" class="m-t-20">
                                            <div class="row">
                                                <div class="col-sm-9 todo-inputbar">
                                                    <input type="text" id="todo-input-text" name="todo-input-text" class="form-control" placeholder="Add new todo">
                                                </div>
                                                <div class="col-sm-3 todo-send">
                                                    <button class="btn-primary btn-block btn waves-effect waves-light" type="button" id="todo-btn-submit">Add</button>
                                                </div>
                                            </div>
                                        </form> 
                                    </div> 
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <footer class="footer">
                    2016 - 2019 © Moltran.
                </footer>
             

            </div>
               
             <div class="side-bar right-bar nicescroll">
                <h4 class="text-center">Chat</h4>
                <div class="contact-list nicescroll">
                    <ul class="list-group contacts-list">
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-1.jpg" alt="">
                                </div>
                                <span class="name">Chadengle</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <span class="name">Tomaslau</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <span class="name">Stillnotdavid</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-4.jpg" alt="">
                                </div>
                                <span class="name">Kurafire</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-5.jpg" alt="">
                                </div>
                                <span class="name">Shahedk</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-6.jpg" alt="">
                                </div>
                                <span class="name">Adhamdannaway</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-7.jpg" alt="">
                                </div>
                                <span class="name">Ok</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-8.jpg" alt="">
                                </div>
                                <span class="name">Arashasghari</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-9.jpg" alt="">
                                </div>
                                <span class="name">Joshaustin</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-10.jpg" alt="">
                                </div>
                                <span class="name">Sortino</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                    </ul>  
                </div>
             </div>
            
<?= $this->endSection() ?>
