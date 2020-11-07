<?php
namespace Lggr;

require __DIR__ . '/../vendor/autoload.php';

$faker = \Faker\Factory::create();
$faker->seed(123);

$arrayFacilities = array(
	'kern','user','mail','daemon','auth','syslog','lpr','news','uucp','authpriv','ftp','cron','local0','local1','local2','local3','local4','local5','local6','local7'
);
$arrayLevels = array(
	'emerg','alert','crit','err','warning','notice','info','debug'
);
$arrayServers = array();

try {
	$config = new CiConfig();
	$db = new \mysqli($config->getDbHost(), $config->getDbUser(),
		$config->getDbPwd(), $config->getDbName());
	if($db->connect_error) {
		die('Connection error ' . $db->connect_error . ' to ' .
			$config->getDbUser() . '@' . $config->getDbHost());
	}
	$db->set_charset('utf8');

	trimAll($db);

	$arrayServers = makeServers($faker, $db, 5);
	makeEntries($faker, $db, 10000);
}
catch (LggrException $e) {
  die($e->getMessage());
} // try

function trimAll($db) {
	$db->query("TRUNCATE TABLE servers");
	$db->query("TRUNCATE TABLE newlogs");
}

function makeServers($faker, $db, $count=10) {
	$a = array();
	for($i=0; $i<$count; $i++) {
		$name = $faker->domainWord;
		echo $name . ", ";
		$sql = "INSERT INTO servers (name) VALUES ('" . $name . "')";
		$db->query($sql);
		$id = $db->insert_id;
		$a[$id] = $name;
	}
	return $a;
}

function makeEntries($faker, $db, $count=1000) {
	global $arrayFacilities, $arrayLevels, $arrayServers;

	if (!($stmt = $db->prepare("INSERT INTO newlogs (date, facility, level, host, program, pid, message, idhost) VALUES (?,?,?,?,?,?,?,?)"))) {
		    echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}
	$dDate = "";
	$dFacility = "";
	$dLevel = "";
	$dHost = "";
	$dProgram = "";
	$dPid = 0;
	$dMessage = "";
	$dIdhost = 0;
	if (!$stmt->bind_param("sssssisi", $dDate,$dFacility,$dLevel,$dHost,$dProgram,$dPid,$dMessage,$dIdhost)) {
		    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	for($i=0; $i<$count; $i++) {
		$dDate = $faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now')->format('Y-m-d H:i:s');
		$dFacility = array_rand($arrayFacilities);
		$dLevel = array_rand($arrayLevels);
		$dProgram = $faker->domainWord;
		$dPid = rand(1, 65500);
		$dMessage = $faker->text;
		$dIdhost = array_rand($arrayServers);
		$dHost = $arrayServers[$dIdhost];

		$stmt->execute();
	}
	$stmt->close();
}

