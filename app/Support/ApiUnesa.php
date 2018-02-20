<?php
/**
 * Created by PhpStorm.
 * User: Rafy
 * Date: 19/02/2018
 * Time: 20.13
 */

namespace App\Support;


class ApiUnesa
{
    public function __construct()
    {
        error_reporting(0);
    }

    public static function getDetailMahasiswa($nim)
    {
        $params = Array(
            'kondisi' => 'detil_mahasiswa',
            'nipd' => $nim
        );
        $data = (new static())->kirim_data('post', $params);
        return json_decode($data['isi']);
    }

    public static function getFakultasProdi()
    {
        $params = Array(
            'kondisi' => 'master_prodi'
        );
        $data = (new static())->kirim_data('post', $params);
        $data = json_decode($data['isi']);
        $data = collect($data);
        return $data;
    }

    public static function getMahasiswaPerProdi($id_prodi)
    {
        $params = Array(
            'kondisi' => 'mhs_per_prodi',
            'id_prodi' => $id_prodi
        );
        $data = (new static())->kirim_data('post', $params);
        $data = json_decode($data['isi']);
        $data = collect($data);
        return $data;
    }

    private function baca_header($x)
    {
        $h = preg_split('/\r\n|[\r\n]/', $x);
        $header = array();
        $cookies = array();
        foreach ($h as $v) {
            $d = explode(": ", $v);
            $dp = strtolower($d[0]);
            $bl = $d[1];
            if ($dp == 'set-cookie') {
                $cookies[] = trim($bl);
            } else {
                $header[$dp] = trim($bl);
            }
        }
        $header['set-cookie'] = $cookies;
        return $header;
    }

    private function kirim_data($method = 'get', &$data)
    {
        $url = "https://siakadu.unesa.ac.id/api/apiunesa";
        error_reporting(0);
        global $agent;
        if ($agent == '') {
            $agent = $_SERVER['HTTP_USER_AGENT'];
        }
        $t = parse_url($url);
        $hostname = $t['host'];
//        $port=(int)$t['port'];
//        if($port > 0){
//            $url=str_replace(":".$port,"",$url);
//        }

        $post = curl_init();
//        if ($port > 0) {
//            curl_setopt($post, CURLOPT_PORT, $port);
//        }
        curl_setopt($post, CURLOPT_URL, $url);
        if ($method == 'post') {
            $fields = '';
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $fields .= $k . '=' . urlencode($v) . '&';
                    }
                } else {
                    $fields .= urlencode($key) . '=' . urlencode($value) . '&';
                }
            }
            $fields = trim($fields, '&');
            curl_setopt($post, CURLOPT_POST, count($data));
            curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        }
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_USERAGENT, $agent);
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_HEADER, 1);
//        $gh=getallheaders();
        $header_reg = array();
//        $ilang = array('content-length', 'host', 'cookie', 'user-agent');
//        foreach ($gh as $t => $v) {
//            if (!in_array(strtolower($t), $ilang)) {
//                $header_reg[] = "$t: $v";
//            }
//        }


        curl_setopt($post, CURLOPT_HTTPHEADER, $header_reg);
        $result = curl_exec($post);
        curl_close($post);

        $delimiter = "\r\n\r\n";
        while (preg_match('#^HTTP/[0-9\\.]+\s+100\s+Continue#i', $result)) {
            $tmp = explode($delimiter, $result, 2);
            $result = $tmp[1];
        }
        $x = explode($delimiter, $result, 2);
        $get_header = $this->baca_header($x[0]);
        $isi = $x[1];

        if (array_key_exists("location", $get_header)) {
            $ew = array();
            $xx = kirim_data($get_header['location'], '', $ew);
            return $xx;
            exit();
        }
        if ($get_header['content-encoding'] == 'gzip') {
            $isi = un_gzip($isi);
        }
        $xx['header'] = $get_header;
        $xx['isi'] = $isi;
        return $xx;
    }
}