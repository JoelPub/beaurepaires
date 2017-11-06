<?php
if (!isset($_GET['type'])) die();
$type = $_GET['type'];

if ($type == 'varnishall') {
    // Varnish // beau.local/beau_clearcache.php?type=varnishall
    $servers = array('apdgy-avmh-varnish-01', 'apdgy-avmh-varnish-02');
    foreach ($servers as $server) {
        $curl = curl_init("http://$server:6082/");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PURGE");
        curl_exec($curl);
    }
}

if ($type == 'opcodelocal') {
    // Opcode Cache // beau.local/beau_clearcache.php?type=opcodelocal
    opcache_reset();
}

if ($type == 'magentolocal') {
// Magento// beau.local/beau_clearcache.php?type=magentolocal
    require_once('app/Mage.php');
    $app = Mage::app();
    if ($app != null) {
        $cache = $app->getCache();
        if ($cache != null) {
            $cache->clean();
        }
    } 
}