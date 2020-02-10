<?php
    $title_meta = 'Panel Dashboard for Tracksz, a Multiple Market Inventory Management Service';
    $description_meta = 'Panel Dashboard for Tracksz, a Multiple Market Inventory Management Service';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>

<!-- .wrapper -->
<div class="wrapper">
    <!-- .page -->
    <div class="page">
        <?php if(\Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                 \Delight\Cookie\Cookie::get('tracksz_active_store') > 0 &&
                 \Delight\Cookie\Session::get('current_page') == '/account'): ?>
        <div class="page-message" role="alert">
            <i class="fa fa-fw fa-shopping-cart"></i> <?=_('Current Active Store')?>: <span class="text-muted-dark"><?=urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name'))?></span> <a href="/account/stores" class="btn btn-sm btn-warning circle ml-3"><?=_('Change Active Store')?></a> <a href="#" class="btn btn-sm btn-icon btn-warning ml-1" aria-label="Close" onclick="$(this).parent().fadeOut()"><span aria-hidden="true"><i class="fa fa-times"></i></span></a>
        </div><!-- /.page-message -->
        <?php elseif(\Delight\Cookie\Session::get('current_page') == '/account'): ?>
            <div class="page-message" role="alert">
                <i class="fa fa-fw fa-shopping-cart"></i> <?=_('No Active Store Selected')?>: <span class="text-muted-dark"><?=urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name'))?></span> <a href="/account/stores" class="btn btn-sm btn-danger circle ml-3"><?=_('Select Active Store')?></a> <a href="#" class="btn btn-sm btn-icon btn-danger ml-1" aria-label="Close" onclick="$(this).parent().fadeOut()"><span aria-hidden="true"><i class="fa fa-times"></i></span></a>
            </div><!-- /.page-message -->
        <?php endif; ?>
        <!-- .page-inner -->
        <div class="page-inner">
            <!-- .page-title-bar -->
            <header class="page-title-bar">
                <div class="d-flex flex-column flex-md-row">
                    <p class="lead">
                        <span class="font-weight-bold"><?=\Delight\Cookie\Session::get('member_name')?></span> <span class="d-block text-muted"><?=_('Here’s what’s happening with your business today.')?></span>
                    </p>
                    <div class="ml-auto">
                        <strong>Active Store:</strong>&nbsp;&nbsp;
                        <?php if(\Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                            \Delight\Cookie\Cookie::get('tracksz_active_store') > 0): ?>
                            <a href="/account/stores" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="<?=_('Click to Change Active Store')?>"><i class="fas fa-shopping-cart ml-1"></i> <?=urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name'))?></a>
                        <?php else: ?>
                            <a href="/account/stores" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="<?=_('Click to Select Active Store')?>"><?=_('Select Active Store')?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                    <!-- metric row -->
                    <div class="metric-row">
                        <div class="col-lg-9">
                            <div class="metric-row metric-flush">
                                <!-- metric column -->
                                <div class="col">
                                    <!-- .metric -->
                                    <a href="user-teams.html" class="metric metric-bordered align-items-center">
                                        <h2 class="metric-label"> Teams </h2>
                                        <p class="metric-value h3">
                                            <sub><i class="oi oi-people"></i></sub> <span class="value">8</span>
                                        </p>
                                    </a> <!-- /.metric -->
                                </div><!-- /metric column -->
                                <!-- metric column -->
                                <div class="col">
                                    <!-- .metric -->
                                    <a href="user-projects.html" class="metric metric-bordered align-items-center">
                                        <h2 class="metric-label"> Projects </h2>
                                        <p class="metric-value h3">
                                            <sub><i class="oi oi-fork"></i></sub> <span class="value">12</span>
                                        </p>
                                    </a> <!-- /.metric -->
                                </div><!-- /metric column -->
                                <!-- metric column -->
                                <div class="col">
                                    <!-- .metric -->
                                    <a href="user-tasks.html" class="metric metric-bordered align-items-center">
                                        <h2 class="metric-label"> Active Tasks </h2>
                                        <p class="metric-value h3">
                                            <sub><i class="fa fa-tasks"></i></sub> <span class="value">64</span>
                                        </p>
                                    </a> <!-- /.metric -->
                                </div><!-- /metric column -->
                            </div>
                        </div><!-- metric column -->
                        <div class="col-lg-3">
                            <!-- .metric -->
                            <a href="user-tasks.html" class="metric metric-bordered">
                                <div class="metric-badge">
                                    <span class="badge badge-lg badge-success"><span class="oi oi-media-record pulse mr-1"></span> ONGOING TASKS</span>
                                </div>
                                <p class="metric-value h3">
                                    <sub><i class="oi oi-timer"></i></sub> <span class="value">8</span>
                                </p>
                            </a> <!-- /.metric -->
                        </div><!-- /metric column -->
                    </div><!-- /metric row -->
                </div><!-- /.section-block -->
                <!-- grid row -->
                <div class="row">
                    <!-- grid column -->
                    <div class="col-12 col-lg-12 col-xl-4">
                        <!-- .card -->
                        <div class="card card-fluid">
                            <!-- .card-body -->
                            <div class="card-body">
                                <h3 class="card-title mb-4"> Completion Tasks </h3>
                                <div class="chartjs" style="height: 292px">
                                    <canvas id="completion-tasks"></canvas>
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div><!-- /grid column -->
                    <!-- grid column -->
                    <div class="col-12 col-lg-6 col-xl-4">
                        <!-- .card -->
                        <div class="card card-fluid">
                            <!-- .card-body -->
                            <div class="card-body">
                                <h3 class="card-title"> Tasks Performance </h3><!-- easy-pie-chart -->
                                <div class="text-center pt-3">
                                    <div class="chart-inline-group" style="height:214px">
                                        <div class="easypiechart" data-toggle="easypiechart" data-percent="60" data-size="214" data-bar-color="#346CB0" data-track-color="false" data-scale-color="false" data-rotate="225"></div>
                                        <div class="easypiechart" data-toggle="easypiechart" data-percent="50" data-size="174" data-bar-color="#00A28A" data-track-color="false" data-scale-color="false" data-rotate="225"></div>
                                        <div class="easypiechart" data-toggle="easypiechart" data-percent="75" data-size="134" data-bar-color="#5F4B8B" data-track-color="false" data-scale-color="false" data-rotate="225"></div>
                                    </div>
                                </div><!-- /easy-pie-chart -->
                            </div><!-- /.card-body -->
                            <!-- .card-footer -->
                            <div class="card-footer">
                                <div class="card-footer-item">
                                    <i class="fa fa-fw fa-circle text-indigo"></i> 100% <div class="text-muted small"> Assigned </div>
                                </div>
                                <div class="card-footer-item">
                                    <i class="fa fa-fw fa-circle text-purple"></i> 75% <div class="text-muted small"> Completed </div>
                                </div>
                                <div class="card-footer-item">
                                    <i class="fa fa-fw fa-circle text-teal"></i> 60% <div class="text-muted small"> Active </div>
                                </div>
                            </div><!-- /.card-footer -->
                        </div><!-- /.card -->
                    </div><!-- /grid column -->
                    <!-- grid column -->
                    <div class="col-12 col-lg-6 col-xl-4">
                        <!-- .card -->
                        <div class="card card-fluid">
                            <!-- .card-body -->
                            <div class="card-body pb-0">
                                <h3 class="card-title"> Leaderboard </h3><!-- legend -->
                                <ul class="list-inline small">
                                    <li class="list-inline-item">
                                        <i class="fa fa-fw fa-circle text-light"></i> Tasks </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-fw fa-circle text-purple"></i> Completed </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-fw fa-circle text-teal"></i> Active </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-fw fa-circle text-red"></i> Overdue </li>
                                </ul><!-- /legend -->
                            </div><!-- /.card-body -->
                            <!-- .list-group -->
                            <div class="list-group list-group-flush">
                                <!-- .list-group-item -->
                                <div class="list-group-item">
                                    <!-- .list-group-item-figure -->
                                    <div class="list-group-item-figure">
                                        <a href="user-profile.html" class="user-avatar" data-toggle="tooltip" title="Martha Myers"><img src="/assets/images/avatars/uifaces16.jpg" alt=""></a>
                                    </div><!-- /.list-group-item-figure -->
                                    <!-- .list-group-item-body -->
                                    <div class="list-group-item-body">
                                        <!-- .progress -->
                                        <div class="progress progress-animated bg-transparent rounded-0" data-toggle="tooltip" data-html="true" title='&lt;div class="text-left small"&gt;&lt;i class="fa fa-fw fa-circle text-purple"&gt;&lt;/i&gt; 2065&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-teal"&gt;&lt;/i&gt; 231&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-red"&gt;&lt;/i&gt; 54&lt;/div&gt;'>
                                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="73.46140163642832" aria-valuemin="0" aria-valuemax="100" style="width: 73.46140163642832%">
                                                <span class="sr-only">73.46140163642832% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="8.217716115261473" aria-valuemin="0" aria-valuemax="100" style="width: 8.217716115261473%">
                                                <span class="sr-only">8.217716115261473% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="1.92102454642476" aria-valuemin="0" aria-valuemax="100" style="width: 1.92102454642476%">
                                                <span class="sr-only">1.92102454642476% Complete</span>
                                            </div>
                                        </div><!-- /.progress -->
                                    </div><!-- /.list-group-item-body -->
                                </div><!-- /.list-group-item -->
                                <!-- .list-group-item -->
                                <div class="list-group-item">
                                    <!-- .list-group-item-figure -->
                                    <div class="list-group-item-figure">
                                        <a href="user-profile.html" class="user-avatar" data-toggle="tooltip" title="Tammy Beck"><img src="/assets/images/avatars/uifaces15.jpg" alt=""></a>
                                    </div><!-- /.list-group-item-figure -->
                                    <!-- .list-group-item-body -->
                                    <div class="list-group-item-body">
                                        <!-- .progress -->
                                        <div class="progress progress-animated bg-transparent rounded-0" data-toggle="tooltip" data-html="true" title='&lt;div class="text-left small"&gt;&lt;i class="fa fa-fw fa-circle text-purple"&gt;&lt;/i&gt; 1432&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-teal"&gt;&lt;/i&gt; 406&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-red"&gt;&lt;/i&gt; 49&lt;/div&gt;'>
                                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="54.180855088914115" aria-valuemin="0" aria-valuemax="100" style="width: 54.180855088914115%">
                                                <span class="sr-only">54.180855088914115% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="15.361331819901627" aria-valuemin="0" aria-valuemax="100" style="width: 15.361331819901627%">
                                                <span class="sr-only">15.361331819901627% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="1.853953840332955" aria-valuemin="0" aria-valuemax="100" style="width: 1.853953840332955%">
                                                <span class="sr-only">1.853953840332955% Complete</span>
                                            </div>
                                        </div><!-- /.progress -->
                                    </div><!-- /.list-group-item-body -->
                                </div><!-- /.list-group-item -->
                                <!-- .list-group-item -->
                                <div class="list-group-item">
                                    <!-- .list-group-item-figure -->
                                    <div class="list-group-item-figure">
                                        <a href="user-profile.html" class="user-avatar" data-toggle="tooltip" title="Susan Kelley"><img src="/assets/images/avatars/uifaces17.jpg" alt=""></a>
                                    </div><!-- /.list-group-item-figure -->
                                    <!-- .list-group-item-body -->
                                    <div class="list-group-item-body">
                                        <!-- .progress -->
                                        <div class="progress progress-animated bg-transparent rounded-0" data-toggle="tooltip" data-html="true" title='&lt;div class="text-left small"&gt;&lt;i class="fa fa-fw fa-circle text-purple"&gt;&lt;/i&gt; 1271&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-teal"&gt;&lt;/i&gt; 87&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-red"&gt;&lt;/i&gt; 82&lt;/div&gt;'>
                                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="52.13289581624282" aria-valuemin="0" aria-valuemax="100" style="width: 52.13289581624282%">
                                                <span class="sr-only">52.13289581624282% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="3.568498769483183" aria-valuemin="0" aria-valuemax="100" style="width: 3.568498769483183%">
                                                <span class="sr-only">3.568498769483183% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="3.3634126333059884" aria-valuemin="0" aria-valuemax="100" style="width: 3.3634126333059884%">
                                                <span class="sr-only">3.3634126333059884% Complete</span>
                                            </div>
                                        </div><!-- /.progress -->
                                    </div><!-- /.list-group-item-body -->
                                </div><!-- /.list-group-item -->
                                <!-- .list-group-item -->
                                <div class="list-group-item">
                                    <!-- .list-group-item-figure -->
                                    <div class="list-group-item-figure">
                                        <a href="user-profile.html" class="user-avatar" data-toggle="tooltip" title="Albert Newman"><img src="/assets/images/avatars/uifaces18.jpg" alt=""></a>
                                    </div><!-- /.list-group-item-figure -->
                                    <!-- .list-group-item-body -->
                                    <div class="list-group-item-body">
                                        <!-- .progress -->
                                        <div class="progress progress-animated bg-transparent rounded-0" data-toggle="tooltip" data-html="true" title='&lt;div class="text-left small"&gt;&lt;i class="fa fa-fw fa-circle text-purple"&gt;&lt;/i&gt; 1527&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-teal"&gt;&lt;/i&gt; 205&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-red"&gt;&lt;/i&gt; 151&lt;/div&gt;'>
                                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="75.18463810930577" aria-valuemin="0" aria-valuemax="100" style="width: 75.18463810930577%">
                                                <span class="sr-only">75.18463810930577% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="10.093549975381585" aria-valuemin="0" aria-valuemax="100" style="width: 10.093549975381585%">
                                                <span class="sr-only">10.093549975381585% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="7.434761201378631" aria-valuemin="0" aria-valuemax="100" style="width: 7.434761201378631%">
                                                <span class="sr-only">7.434761201378631% Complete</span>
                                            </div>
                                        </div><!-- /.progress -->
                                    </div><!-- /.list-group-item-body -->
                                </div><!-- /.list-group-item -->
                                <!-- .list-group-item -->
                                <div class="list-group-item">
                                    <!-- .list-group-item-figure -->
                                    <div class="list-group-item-figure">
                                        <a href="user-profile.html" class="user-avatar" data-toggle="tooltip" title="Kyle Grant"><img src="/assets/images/avatars/uifaces19.jpg" alt=""></a>
                                    </div><!-- /.list-group-item-figure -->
                                    <!-- .list-group-item-body -->
                                    <div class="list-group-item-body">
                                        <!-- .progress -->
                                        <div class="progress progress-animated bg-transparent rounded-0" data-toggle="tooltip" data-html="true" title='&lt;div class="text-left small"&gt;&lt;i class="fa fa-fw fa-circle text-purple"&gt;&lt;/i&gt; 643&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-teal"&gt;&lt;/i&gt; 265&lt;br&gt;&lt;i class="fa fa-fw fa-circle text-red"&gt;&lt;/i&gt; 127&lt;/div&gt;'>
                                            <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="36.89041881812966" aria-valuemin="0" aria-valuemax="100" style="width: 36.89041881812966%">
                                                <span class="sr-only">36.89041881812966% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="15.203671830177854" aria-valuemin="0" aria-valuemax="100" style="width: 15.203671830177854%">
                                                <span class="sr-only">15.203671830177854% Complete</span>
                                            </div>
                                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="7.286288009179575" aria-valuemin="0" aria-valuemax="100" style="width: 7.286288009179575%">
                                                <span class="sr-only">7.286288009179575% Complete</span>
                                            </div>
                                        </div><!-- /.progress -->
                                    </div><!-- /.list-group-item-body -->
                                </div><!-- /.list-group-item -->
                            </div><!-- /.list-group -->
                        </div><!-- /.card -->
                    </div><!-- /grid column -->
                </div><!-- /grid row -->
                <!-- card-deck-xl -->
                <div class="card-deck-xl">
                    <!-- .card -->
                    <div class="card card-fluid">
                        <div class="card-header"> Active Projects </div><!-- .lits-group -->
                        <div class="lits-group list-group-flush">
                            <!-- .lits-group-item -->
                            <div class="list-group-item">
                                <!-- .lits-group-item-figure -->
                                <div class="list-group-item-figure">
                                    <div class="has-badge">
                                        <a href="page-project.html" class="tile tile-md bg-purple">LT</a> <a href="#team" class="user-avatar user-avatar-xs"><img src="/assets/images/avatars/team4.jpg" alt=""></a>
                                    </div>
                                </div><!-- .lits-group-item-figure -->
                                <!-- .lits-group-item-body -->
                                <div class="list-group-item-body">
                                    <h5 class="card-title">
                                        <a href="page-project.html">Looper Admin Theme</a>
                                    </h5>
                                    <p class="card-subtitle text-muted mb-1"> Progress in 74% - Last update 1d </p><!-- .progress -->
                                    <div class="progress progress-xs bg-transparent">
                                        <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="2181" aria-valuemin="0" aria-valuemax="100" style="width: 74%">
                                            <span class="sr-only">74% Complete</span>
                                        </div>
                                    </div><!-- /.progress -->
                                </div><!-- .lits-group-item-body -->
                            </div><!-- /.lits-group-item -->
                            <!-- .lits-group-item -->
                            <div class="list-group-item">
                                <!-- .lits-group-item-figure -->
                                <div class="list-group-item-figure">
                                    <div class="has-badge">
                                        <a href="page-project.html" class="tile tile-md bg-indigo">SP</a> <a href="#team" class="user-avatar user-avatar-xs"><img src="/assets/images/avatars/team1.jpg" alt=""></a>
                                    </div>
                                </div><!-- .lits-group-item-figure -->
                                <!-- .lits-group-item-body -->
                                <div class="list-group-item-body">
                                    <h5 class="card-title">
                                        <a href="page-project.html">Smart Paper</a>
                                    </h5>
                                    <p class="card-subtitle text-muted mb-1"> Progress in 22% - Last update 2h </p><!-- .progress -->
                                    <div class="progress progress-xs bg-transparent">
                                        <div class="progress-bar bg-indigo" role="progressbar" aria-valuenow="867" aria-valuemin="0" aria-valuemax="100" style="width: 22%">
                                            <span class="sr-only">22% Complete</span>
                                        </div>
                                    </div><!-- /.progress -->
                                </div><!-- .lits-group-item-body -->
                            </div><!-- /.lits-group-item -->
                            <!-- .lits-group-item -->
                            <div class="list-group-item">
                                <!-- .lits-group-item-figure -->
                                <div class="list-group-item-figure">
                                    <div class="has-badge">
                                        <a href="page-project.html" class="tile tile-md bg-yellow">OS</a> <a href="#team" class="user-avatar user-avatar-xs"><img src="/assets/images/avatars/team2.png" alt=""></a>
                                    </div>
                                </div><!-- .lits-group-item-figure -->
                                <!-- .lits-group-item-body -->
                                <div class="list-group-item-body">
                                    <h5 class="card-title">
                                        <a href="page-project.html">Online Store</a>
                                    </h5>
                                    <p class="card-subtitle text-muted mb-1"> Progress in 99% - Last update 2d </p><!-- .progress -->
                                    <div class="progress progress-xs bg-transparent">
                                        <div class="progress-bar bg-yellow" role="progressbar" aria-valuenow="6683" aria-valuemin="0" aria-valuemax="100" style="width: 99%">
                                            <span class="sr-only">99% Complete</span>
                                        </div>
                                    </div><!-- /.progress -->
                                </div><!-- .lits-group-item-body -->
                            </div><!-- /.lits-group-item -->
                            <!-- .lits-group-item -->
                            <div class="list-group-item">
                                <!-- .lits-group-item-figure -->
                                <div class="list-group-item-figure">
                                    <div class="has-badge">
                                        <a href="page-project.html" class="tile tile-md bg-blue">BA</a> <a href="#team" class="user-avatar user-avatar-xs"><img src="/assets/images/avatars/bootstrap.svg" alt=""></a>
                                    </div>
                                </div><!-- .lits-group-item-figure -->
                                <!-- .lits-group-item-body -->
                                <div class="list-group-item-body">
                                    <h5 class="card-title">
                                        <a href="page-project.html">Booking App</a>
                                    </h5>
                                    <p class="card-subtitle text-muted mb-1"> Progress in 35% - Last update 4h </p><!-- .progress -->
                                    <div class="progress progress-xs bg-transparent">
                                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="112" aria-valuemin="0" aria-valuemax="100" style="width: 35%">
                                            <span class="sr-only">35% Complete</span>
                                        </div>
                                    </div><!-- /.progress -->
                                </div><!-- .lits-group-item-body -->
                            </div><!-- /.lits-group-item -->
                            <!-- .lits-group-item -->
                            <div class="list-group-item">
                                <!-- .lits-group-item-figure -->
                                <div class="list-group-item-figure">
                                    <div class="has-badge">
                                        <a href="page-project.html" class="tile tile-md bg-teal">SB</a> <a href="#team" class="user-avatar user-avatar-xs"><img src="/assets/images/avatars/sketch.svg" alt=""></a>
                                    </div>
                                </div><!-- .lits-group-item-figure -->
                                <!-- .lits-group-item-body -->
                                <div class="list-group-item-body">
                                    <h5 class="card-title">
                                        <a href="page-project.html">SVG Icon Bundle</a>
                                    </h5>
                                    <p class="card-subtitle text-muted mb-1"> Progress in 32% - Last update 1d </p><!-- .progress -->
                                    <div class="progress progress-xs bg-transparent">
                                        <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="461" aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                            <span class="sr-only">32% Complete</span>
                                        </div>
                                    </div><!-- /.progress -->
                                </div><!-- .lits-group-item-body -->
                            </div><!-- /.lits-group-item -->
                            <!-- .lits-group-item -->
                            <div class="list-group-item">
                                <!-- .lits-group-item-figure -->
                                <div class="list-group-item-figure">
                                    <div class="has-badge">
                                        <a href="page-project.html" class="tile tile-md bg-pink">SP</a> <a href="#team" class="user-avatar user-avatar-xs"><img src="/assets/images/avatars/team4.jpg" alt=""></a>
                                    </div>
                                </div><!-- .lits-group-item-figure -->
                                <!-- .lits-group-item-body -->
                                <div class="list-group-item-body">
                                    <h5 class="card-title">
                                        <a href="page-project.html">Syrena Project</a>
                                    </h5>
                                    <p class="card-subtitle text-muted mb-1"> Progress in 93% - Last update 8h </p><!-- .progress -->
                                    <div class="progress progress-xs bg-transparent">
                                        <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="3981" aria-valuemin="0" aria-valuemax="100" style="width: 93%">
                                            <span class="sr-only">93% Complete</span>
                                        </div>
                                    </div><!-- /.progress -->
                                </div><!-- .lits-group-item-body -->
                            </div><!-- /.lits-group-item -->
                        </div><!-- /.lits-group -->
                    </div><!-- /.card -->
                    <!-- .card -->
                    <div class="card card-fluid">
                        <div class="card-header"> Active Tasks:To-Dos </div><!-- .card-body -->
                        <div class="card-body">
                            <!-- .todo-list -->
                            <div class="todo-list">
                                <!-- .todo-header -->
                                <div class="todo-header"> Looper Admin Theme (1/3) </div><!-- /.todo-header -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo1"> <label class="custom-control-label" for="todo1">Eat corn on the cob</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo2" checked> <label class="custom-control-label" for="todo2">Mix up a pitcher of sangria</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo3"> <label class="custom-control-label" for="todo3">Have a barbecue</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo4"> <label class="custom-control-label" for="todo4">Ride a roller coaster — <span class="text-red small">Overdue in 3 days</span></label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo-header -->
                                <div class="todo-header"> Smart Paper (0/2) </div><!-- /.todo-header -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo5"> <label class="custom-control-label" for="todo5">Bring a blanket and lie on the grass at an outdoor concert</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo6"> <label class="custom-control-label" for="todo6">Collect seashells at the beach</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo7"> <label class="custom-control-label" for="todo7">Swim in a lake</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                                <!-- .todo -->
                                <div class="todo">
                                    <!-- .custom-control -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todo8"> <label class="custom-control-label" for="todo8">Get enough sleep!</label>
                                    </div><!-- /.custom-control -->
                                </div><!-- /.todo -->
                            </div><!-- /.todo-list -->
                        </div><!-- /.card-body -->
                        <!-- .card-footer -->
                        <div class="card-footer">
                            <a href="#" class="card-footer-item">View all <i class="fa fa-fw fa-angle-right"></i></a>
                        </div><!-- /.card-footer -->
                    </div><!-- /.card -->
                </div><!-- /card-deck-xl -->
            </div><!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>


<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/vendor/flatpickr/flatpickr.min.js"></script>
<script src="/assets/vendor/easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="/assets/vendor/chart.js/Chart.min.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js') ?>
<script src="/assets/javascript/pages/dashboard.js"></script>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>

<?=$this->stop()?>
