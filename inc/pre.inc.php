<?php

/**
 * @file pre.inc.php
 * @brief Preloaded function to start a page output
 */
if(!file_exists(__DIR__ . '/../vendor/autoload.php')) {
	echo "autoload.php missing, did you run 'composer install'?";
	exit;
}
require_once __DIR__ . '/../vendor/autoload.php';

$config = new \Lggr\Config();

session_start();

if (isset($_SESSION[\Lggr\LggrState::SESSIONNAME])) {
    $state = $_SESSION[\Lggr\LggrState::SESSIONNAME];
} else {
    $state = new \Lggr\LggrState();
}

// if

// Uebersetzungen via gettext vorbereiten
/*
 * Auf dem Server ausführen:
 * locale -a
 * sollte ergeben:
 * ar_AE.utf8
 * C
 * C.UTF-8
 * de_DE.utf8
 * en_GB.utf8
 * en_US.utf8
 * fr_FR.utf8
 * POSIX
 *
 * Ansonsten via dpkg-reconfigure locales die fehlenden locales nacherzeugen!
 */
const MESSAGES = 'messages';
$lang = $config->getLocale() . '.UTF-8';
putenv("LC_ALL=$lang");
$rc = setlocale(LC_ALL, $lang);
if (! $rc) {
    error_log("setlocale failed! $lang");
}
bindtextdomain(MESSAGES, __DIR__ . '/../locale');
bind_textdomain_codeset(MESSAGES, 'UTF-8');
textdomain(MESSAGES);

define('TAG_ARIALABEL', '" aria-label="');

