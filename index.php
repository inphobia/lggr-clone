<?php

spl_autoload_register(function($class) {
	include 'inc/' . strtolower($class) . '_class.php';
});

require 'tpl/head.inc.php';

session_start();

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if

$l = null;
try {
	$config = new Config();
	$l = new Lggr($state, $config);

	$aLevels = $l->getLevels();
	$aServers = $l->getServers();
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

$aRanges = array(
	'1' => 'This hour',
	'24' => 'Today',
	'168' => 'Week'
);

$page = $state->getPage();

try {
	if($state->isSearch()) {

		$aEvents = $l->getText($state->getSearch(), $state->getSearchProg(), $page*LggrState::PAGELEN, LggrState::PAGELEN);
		$searchvalue = htmlentities($state->getSearch());
		$searchvalueprog = htmlentities($state->getSearchProg());
		$isSearch=true;
		$sFilter = 'Text search for';
		if('' != $state->getSearch()) $sFilter .= ' message <strong>' . $searchvalue . '</strong>';
		if('' != $state->getSearchProg()) $sFilter .= ' program <strong>' . $searchvalueprog . '</strong>';

	} elseif($state->isHost() || $state->isLevel()) {

		$host = $state->getHost();
		$level = $state->getLevel();

		$aEvents = $l->getFiltered($host, $level, $page*LggrState::PAGELEN, LggrState::PAGELEN);
		$searchvalue='';
		$isSearch=false;
		$sFilter='';
		if($state->isHost())
			$sFilter .= 'Filter by server <strong>' . htmlentities($state->getHost()) . '</strong>';
		if($state->isLevel())
			$sFilter .= 'Filter by level <strong>' . htmlentities($state->getLevel()) . '</strong>';

	} else {

		$sFilter = null;

		$aEvents = $l->getLatest($page*LggrState::PAGELEN, LggrState::PAGELEN);
		$searchvalue='';
		$isSearch=false;

	} // if search
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

require 'tpl/nav.inc.php';
?>

    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h2><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Levels</h2>
          <div class="progress">
<?php
$aLevelCount = array();
foreach($aLevels as $level) {
	$aLevelCount[$level->level] = $level->c;
	switch($level->level) {
	case 'emerg':
	case 'crit':
	case 'err':
		$label='progress-bar-danger';
		break;
	case 'warning':
		$label='progress-bar-warning';
		break;
	case 'notice':
		$label='progress-bar-primary';
		break;
	case 'info':
		$label='progress-bar-success';
		break;
	default: $label='';
	} // switch

	echo <<<EOL
<div class="progress-bar $label" style="width: {$level->f}%" title="{$level->level} {$level->f}%">
<span class="sr-only">{$level->f}%</span>
</div>
EOL;
} // foreach
?>
</div>
	<p>Distribution of selected event levels.</p>
	<p class="lggr-level-buttons">
<?php
if(isset($aLevelCount['emerg'])) {
	echo '<button class="btn btn-sm btn-danger" type="button">Emergency <span class="badge">' . $aLevelCount['emerg'] . '</span></button> ';
}
if(isset($aLevelCount['crit'])) {
	echo '<button class="btn btn-sm btn-danger" type="button">Critical <span class="badge">' . $aLevelCount['crit'] . '</span></button> ';
}
if(isset($aLevelCount['err'])) {
	echo '<button class="btn btn-sm btn-danger" type="button">Error <span class="badge">' . $aLevelCount['err'] . '</span></button> ';
}
if(isset($aLevelCount['err'])) {
	echo '<button class="btn btn-sm btn-warning" type="button">Warning <span class="badge">' . $aLevelCount['warning'] . '</span></button> ';
}
if(isset($aLevelCount['notice'])) {
	echo '<button class="btn btn-sm btn-primary" type="button">Notice <span class="badge">' . $aLevelCount['notice'] . '</span></button> ';
}
?>
	</p>
        </div>

        <div class="col-md-4">
          <h2><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> Servers</h2>
<?php
foreach($aServers as $server) {
	if($server->f < 5) continue;

        echo <<<EOL
<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="{$server->f}" aria-valuemin="0" aria-valuemax="100" style="width: {$server->f}%; min-width: 3em" title="{$server->host} {$server->f}%">
{$server->host} {$server->f}%
	</div>
</div>
EOL;
} // foreach
?>
          <p>Most reporting servers (5% or more).</p>
        </div>

        <div class="col-md-4">
          <h2><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter</h2>
<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
    Server
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
<?php
foreach($aServers as $server) {
	echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./do.php?a=host&host=' . urlencode($server->host) . '">' . $server->host . '</a></li>';
} // foreach
?>
  </ul>
</div><!-- dropdown -->

<p><div class="btn-group btn-group-xs" role="group" aria-label="level">
<?php
foreach($aLevels as $level) {
	if($state->isLevel() && ($level->level == $state->getLevel())) {
		echo '<button type="button" class="btn btn-primary newlog-level">' . $level->level . '</button>';
	} else {
		echo '<button type="button" class="btn btn-default newlog-level">' . $level->level . '</button>';
	}
} // foreach
?>
</div></p>

<p><div class="btn-group" role="group" aria-label="range">
<?php
foreach($aRanges as $rangeValue => $rangeText) {
	if($state->getRange() == $rangeValue) {
		echo '<button type="button" class="btn btn-primary newlog-range" data-range="' . $rangeValue . '">' . $rangeText . '</button>';
	} else {
		echo '<button type="button" class="btn btn-default newlog-range" data-range="' . $rangeValue . '">' . $rangeText . '</button>';
	}
} // foreach
?>
</div></p>
<p><a type="button" role="button" href="./do.php?a=reset" class="btn btn-default">
  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Reset
</a></p>
        </div>
      </div>
    </div> <!-- /container -->

<div class="container">
<?php

if(null != $sFilter) {
	echo '<div class="alert alert-info" role="alert">' . $sFilter . '</div>';
} // if

if(0 == count($aEvents)) {
	echo '<div class="alert alert-danger" role="alert">empty result</div>';
} // if

?>
</div>

<div class="container datablock">
<?php

include 'tpl/paginate.inc.php';

$i=0;
foreach($aEvents as $event) {
	$i++;

	if(0 == $i % 2) {
		$rowclass='even';
	} else {
		$rowclass='odd';
	} // if

	switch($event->level) {
	case 'emerg': $label = '<span class="label label-danger">Emergency</span>'; break;
	case 'crit': $label = '<span class="label label-danger">Critical</span>'; break;
	case 'err': $label = '<span class="label label-danger">Error</span>'; break;
	case 'warning': $label = '<span class="label label-warning">Warning</span>'; break;
	case 'notice': $label='<span class="label label-primary">Notice</span>'; break;
	case 'info': $label = '<span class="label label-success">Info</span>'; break;
	default: $label = '<span class="label label-default">' . $event->level . '</span>';
	} // switch

	$host = htmlentities($event->host);
	$program = htmlentities($event->program);
	$msg = htmlentities($event->message);

	echo <<<EOL
<div class="row datarow $rowclass" data-id="{$event->id}">
	<div class="col-md-2 col-xs-6 newlog-date">{$event->date}</div>
	<div class="col-md-1 col-xs-2">{$event->facility}</div>
	<div class="col-md-1 col-xs-2">$label</div>
	<div class="col-md-1 col-xs-2">$host</div>
	<div class="col-md-2 col-xs-12">$program</div>
	<div class="col-md-5 col-xs-12 newlog-msg" title="$msg"><tt>{$msg}</tt></div>
</div><!-- row -->
EOL;

} // foreach
?>
<div id="dialog" title="Details">I'm a dialog</div>

<?php
include 'tpl/paginate.inc.php';
?>

</div>

<?php
$aPerf = $l->getPerf();
require 'tpl/foot.inc.php'
?>
