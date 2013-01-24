#!/usr/bin/php
<?
require("Net/SSH2.php");

$STARTIP	= 8;
$ENDEDIP	= 250;

$SSH_USER	= 'root';
$SSH_PASSWD	= '';

$NEW_PASSWD	= '';

$sData      = file_get_contents("/etc/hosts");

$nIndex     = 0;
$lsData     = explode("\n", $sData);
foreach ( $lsData as $sLine ) {

    $sLine      = str_replace("\t\t","\t", $sLine);

    $lstemp     = explode("\t", $sLine);
    $sIP        = trim($lstemp[0]);
    $sHostName  = trim($lstemp[1]);
    if ( $sIP == "" || $sHostName == "" || $sIP == "127.0.0.1" || $sIP == '#::1' || $sIP =='172.16.7.7' ) { continue; }
    //if ( $sIP == "" || $sHostName == "" || $sIP == "127.0.0.1" || $sIP == '#::1' ) { continue; }

    $sServerIP  = "$sIP";

    $ssh        = new Net_SSH2($sServerIP);
    if (!$ssh->login($SSH_USER, $SSH_PASSWD)) {
        echo "[$sServerIP]ssh login Failed\n";
        continue;
    }

    $sRet   = $ssh->exec("echo '$NEW_PASSWD' | passwd root --stdin");
    if ( strstr($sRet, "FAILED") || strstr($sRet, "실패") ) {
        echo "[$sServerIP]ssh login Failed\n";
    }

    $ssh->exec('exit');

	echo "[$sServerIP]Complete $sRet\n";

}
?>
