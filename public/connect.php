<?php declare(strict_types = 1);
/*
 * used to route ?code=adfasdfasdf&whatever to Stripe Connect route in app
 * ? was causing problem with defining route and htaccess removing last /
 *
 */

if (!$_SERVER['QUERY_STRING']) {
    $_SERVER['QUERY_STRING'] = 'novalue=1';
}
header('Location: https://dev.tracksz.com/account/connect/'.$_SERVER['QUERY_STRING']);
//header('Location: /account/connect/'.$_SERVER['QUERY_STRING']);
exit();