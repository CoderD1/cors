<?
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST,GET,OPTIONS');

    $time0 = microtime(true);

    // validates
    $get  = isset($_GET) && $_GET;
    $post = isset($_POST) && $_POST;
    if (!$get || !$post) exit();

    $params = (array)json_decode(file_get_contents('php://input'));

    // more validates
    $ep  = (isset($params['ep'])) ? $params['ep'] : '';
    $xck = (isset($params['xck'])) ? $params['xck'] : '';
    $key = (isset($_GET['key'])) ? $_GET['key'] : '';

    if (!$xck || !$key || $xck !== $key) exiting("ERROR: INVALID KEY");
    if (!$ep) exiting("ERROR: NO EP");

    $curl = curl_init($ep);

    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_ENCODING ,'');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // do split, separated by 2 CRs
    list($header, $contents) = preg_split('/([\r\n][\r\n])\\1/', curl_exec($curl), 2);
    curl_close($curl);

    $elapsed = microtime(true) - $time0;

    if (!$contents) $contents = 'ERROR: NO DATA!';
    printf('%s || %s ',  $elapsed, trim($contents));


    function exiting($message) {
        print " 0 || $message";
        exit();
    }
