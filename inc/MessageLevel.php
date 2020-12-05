<?php
namespace Lggr;

/*
 * @brief Emulates the syslog message levels.
 */
abstract class MessageLevel {
    const EMERG = 'emerg';
    const CRIT = 'crit';
    const ERR = 'err';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
}
