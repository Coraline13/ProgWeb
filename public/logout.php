<?php
require_once dirname(__FILE__).'/../lib/api.php';

global $_LOCALE, $_SUPPORTED_LOCALES;

check_method(["GET"]);

$token = isset($_COOKIE[CFG_COOKIE_AUTH]) ? $_COOKIE[CFG_COOKIE_AUTH] : null;
$session = Session::getByToken($token);
if ($session != null) {
    log_info($session->getUser()->getUsername()." closed session ".$session->getId());
    $session->delete();
}

setcookie(CFG_COOKIE_AUTH, null, -1, "/", "", $GLOBALS['secure'], true);
http_redirect("#auth");
