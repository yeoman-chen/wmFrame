<?php

$data = ['code' => "110102", 'data' => ["id" => 1]];
// Json编码得到包体
$body_json_str = json_encode($data);
$body_json_str = '{"code":"100101","act":"register","data":{"userName":"const12345","pwd":"123456","rePwd":"123456"}}';
// 计算整个包的长度，首部4字节+包体字节数
$total_length = 4 + strlen($body_json_str);
//echo strlen($body_json_str)."\n";
// 返回打包的数据
$res = pack('L', $total_length) . $body_json_str;
/*echo pack('A', $total_length) . $body_json_str."\n";
echo pack('a', $total_length) . $body_json_str."\n";
echo pack('C', $total_length) . $body_json_str."\n";
echo pack('c', $total_length) . $body_json_str."\n";
echo pack('h', $total_length) . $body_json_str."\n";
echo pack('H', $total_length) . $body_json_str."\n";
echo pack('f', $total_length) . $body_json_str."\n";
echo pack('d', $total_length) . $body_json_str."\n";
echo pack('l', $total_length) . $body_json_str."\n";
echo pack('L', $total_length) . $body_json_str."\n";

die;*/
//echo $res."\n";die;
/*echo $res."\n";
echo strlen($res)."\n";
$unpack_data = unpack('Ntotal_length', $res);
$body_json_str = substr($res, 4);
$res = json_decode($body_json_str, true);
print_r($res);die;*/

$dada = ['d' => 11, 'b' => "dada"];
error_reporting(E_ALL);
set_time_limit(0);
//echo "<h2>TCP/IP Connection</h2>\n";

//echo $res;die;
$port = 8282;
//$ip   = "119.23.204.138";
$ip   = "127.0.0.1";

/*
+-------------------------------
 *    @socket连接整个过程
+-------------------------------
 *    @socket_create
 *    @socket_connect
 *    @socket_write
 *    @socket_read
 *    @socket_close
+--------------------------------
 */

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
    echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
} else {
    echo "OK.\n";
}
sleep(2);
$startTime = time();
//echo "试图连接 '$ip' 端口 '$port'...\n";
$result = socket_connect($socket, $ip, $port);
if ($result < 0) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
} else {
    echo "连接OK\n";
}

//$in = '{"ctr": "user","act": "login","data": {"userName": "test123","pwd": 123456}}'."\n";
//$in = '{"ctr": "userEquipment","act": "getList","data": {"userId": 18}}'."##".'{"ctr": "userEquipment","act": "getList","data": {"userId": 18}}'."##";
$in  = '{"ctr": "userEquipment","act": "getList","data": {"userId": 18}}' . "\n";
$out = '';
$in  = $res;
$in = 'g{"code":"100101","act":"register","data":{"userName":"const12345","pwd":"123456","rePwd":"123456"}}';
for ($i = 0; $i < 2; $i++) {
    echo $in."\n";
    $res = socket_write($socket, $in, strlen($in));
    if ($res) {
        $out = socket_read($socket, 8192);
        echo $out."\n";
    }
    sleep(2);
}
/*if(!socket_write($socket, $in, strlen($in))) {
echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
}else {
echo "发送到服务器信息成功！\n";
echo "发送的内容为:<font color='red'>$in</font> <br>";
}

while($out = socket_read($socket, 8192)) {
//echo "接收服务器回传信息成功！\n";
echo "接受的内容为:",$out;
}*/

echo $end = time() - $startTime;
echo "关闭SOCKET...\n";
socket_close($socket);
echo "关闭OK\n";
