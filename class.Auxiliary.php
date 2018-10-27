<?php
/***************************
Card Auxiliary v0.1b

This tool was created for auxiliary the user in creating a tester.

Auxiliary's:
	getToken
	cookieKill
	valid2Captcha
	delimitArgument
	validEmail
	validExternalEmail
	randomCPF
	validCPF
	getrndUserAgent

Copyright 2018 Fantasma All rights reserved.
***************************/
date_default_timezone_set('America/Sao_Paulo');
require("class.CreditCard.php");
require("obj.php");

class Auxiliary extends CreditCard {
	
	
	/*
	*@ Get current token defined by start and end of html page. / Captura token definido pelo inicio e fim de uma tag html.
	*@ Returns token or null case fail on get the same.
	*/
	
	public function getToken($string, $inicio, $fim, $numero){
		$str = explode($inicio, $string);
		$str = explode($fim, $str[$numero]);
		return $str[0];
	}
	
	/*
	*@ Delete cookie inserted. / Apaga cookie inserido.
	*@ Not return
	*/
	
	public function cookieKill($nome){
		unlink($nome);
	}
	
	/*
	*@ Check if PublishKey of 2Captcha is valid or invalid. / Verifica se a key está valida ou inválida.
	*@ Returns in int
	*/
	
	public function valid2Captcha($publishKey){
		if(!$publishKey){
			return 'Do you not insert your publishKey.';
		}else{
			$str = json_decode(file_get_contents("http://2captcha.com/res.php?key=$publishKey&action=getbalance&json=1"));
			return $str->status;
		}
		
	}
	
	/*
	*@ Delimits string in up to 4 different arguments according to the db delimiters. / Delimita em até 4 argumentos de acordo com os delimitadores da db.
	*@ Returns in array
	*/
	
	public function delimitArgument($string, $delimitador){
		if(!$delimitador){
			list($x, $y, $z, $u) = preg_split('/[|:;,-]/', $string);
			return array($x, $y, $z, $u);
		}else{
			return explode($delimitador, $string);
		}
	}
	
	/*
	*@ Validate Email / Validar E-Mail.
	*@ Returns true or false
	*/
	
	public function validEmail($string){
		if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	*@ Validate External Email / Validar E-Mail em link externo.
	*@ Returns true or false
	*/
	
	public function validExternalEmail($url, $validmsg){
		$str = file_get_contents($url);
		if(strpos($str, $validmsg)){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	*@ Generate random CPF / Gera um CPF Aleatório.
	*@ Type 0 = 00000000000
	*@ Type 1 = 000.000.000-00
	*/
	
	public function randomCPF($type){
		for($i=0;$i<3;$i++){
			$v1[$i] = rand(1,9);
			$v2[$i] = rand(1,9);
			$v3[$i] = rand(1,9);
		}
		$soma = $v1[0] * 10;
        $soma += $v1[1] * 9;
        $soma += $v1[2] * 8;
        $soma += $v2[0] * 7;
        $soma += $v2[1] * 6;
        $soma += $v2[2] * 5;
        $soma += $v3[0] * 4;
        $soma += $v3[1] * 3;
        $soma += $v3[2] * 2;
 
        $verif = array(2,3,4,5,6,7,8,9,10);
        foreach ($verif as $num) {
            if($soma % 11 == $num){
                $j = 11 - ($soma % 11);
            }elseif ($soma % 11<=2) {
                $j = 0;
            }
        }
        $soma2 = $v1[0] * 11;
        $soma2 += $v1[1] * 10;
        $soma2 += $v1[2] * 9;
        $soma2 += $v2[0] * 8;
        $soma2 += $v2[1] * 7;
        $soma2 += $v2[2] * 6;
        $soma2 += $v3[0] * 5;
        $soma2 += $v3[1] * 4;
        $soma2 += $v3[2] * 3;
        $soma2 += $j * 2;
        foreach ($verif as $num) {
            if($soma2 % 11 ==$num){
                $k = 11- ($soma2 % 11);
            }elseif($soma2 % 11 <= 2){
                $k = 0;
            }
        }
 
   
		$p1 = implode('',$v1);
		$p2 = implode('',$v2);
		$p3 = implode('',$v3);
		
		if(!$type){
			return $p1.$p2.$p3.$j.$k;
		}else{
			return "$p1.$p2.$p3-$j$k";
		}
	}
	
	/*
	*@ CPF Validator / Valida o CPF Inserido no argumento da função.
	*@ Returns true case valid or null case invalid
	*/
	
	public function validCPF($cpf){
		$cpf = preg_replace( '/[^0-9]/is', '', $cpf );
		
		if (strlen($cpf) != 11) {
			return false;
		}else if (preg_match('/(\d)\1{10}/', $cpf)) {
			return false;
		}
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf{$c} * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf{$c} != $d) {
				return false;
			}
		}
		return true;
	}
	
	/*
	*@ Get Random UserAgent / Pegar um UserAgent Aleatório.
	*@ Returns UserAgent random
	*/
	
	public function getrndUserAgent(){
		global $user_agents;
		return $user_agents[array_rand($user_agents)];
	}
	
	/*
	*@ Get Random Name and Last / Pegar um Nome ou Sobrenome Aleatório.
	*@ Returns Name and Last random
	*/
	
	public function randomName($numero){
		if(!$numero){
			throw new Exception('Insert 1 or 2 in argument');
		}
		$lista = file("others/last_names.txt");
		$nome = $lista[rand(0, count($lista) - 1)];
		$sobre = $lista[rand(0, count($lista) - 1)];
		if($numero < 2){
			return $nome;
		}else{
			return $nome.' '.$sobre;
		}
		 
	}
	
	/*
	*@ Personal cURL / cURL Personalizado
	*@ Attemption: $url required!!! and $header must be Boolean.
	*@ false = not using, or set argument for use the same
	*/
	
	public function personalCurl($url, $header = false, $headers = false, $useragent = false, $ssl = false, $proxy = false, $cookie = false, $postdata = false){
		if(!$url){
			throw new Exception('Insert url for access by cURL.');
		}
		
		if(!is_bool($header)){
			throw new Exception('Variable header is not boolean.');
		}
		
		$pCURL = curl_init($url);
		$opcoesbasicas = array(
            CURLOPT_HEADER => $header,
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true
        );
		
		if($headers){
			$optheaders = array(
				CURLOPT_HTTPHEADER => $headers
			);
			curl_setopt_array($pCURL, $optheaders);
		}
		
		if($ssl){
			$optssl = array(
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false
			);
			curl_setopt_array($pCURL, $optssl);
		}
		if($proxy){
			$optproxy = array(
				CURLOPT_PROXY => $proxy
			);
			curl_setopt_array($pCURL, $optproxy);
		}
		
		if($cookie){
			$optcookie = array(
				CURLOPT_COOKIE => true,
				CURLOPT_COOKIESESSION => true,
				CURLOPT_COOKIEFILE => $cookie,
				CURLOPT_COOKIEJAR => $cookie
			);
			curl_setopt_array($pCURL, $optcookie);
		}
		
		if($postdata){
			$optpost = array(
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $postdata
			);
			curl_setopt_array($pCURL, $optpost);
		}
		
		curl_setopt_array($pCURL, $opcoesbasicas);
		
		$retorno = curl_exec($pCURL);
		
		if(curl_error($pCURL)){
			return curl_error($pCURL);
		}elseif(is_null($retorno)){
			throw new Exception('Do no have return.');
		}else{
			return $retorno;
		}
	}
	
	/*
	*@ Proxy Check / Testar Proxy
	*@ Attemption: $type required!!!
	*@ Returns in json, status 0 = Proxy Die and status 1 = Proxy Up.
	*/
	
	public function checkProxy($proxy, $type){
		if (preg_match('~\b([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}):([0-9]{1,5}\b)~', $proxy, $matches)) {
			$ip = $matches[1];
			$port = $matches[2];
		}
		
		if($type == "socks4"){
			$opt = array(
				CURLPROXY_SOCKS4 => true
			);
		}else if($type == "socks5"){
			$opt = array(
				CURLPROXY_SOCKS5 => true
			);
		}else if($type == "http" or $type == "https"){
			$opt = array(
				CURLPROXY_HTTP => true
			);
		}else{
			throw new Exception('Do no set proxy type.');
		}
		
		$pCURL = curl_init("https://jsonip.com/");
		curl_setopt($pCURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($pCURL, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($pCURL, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($pCURL, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($pCURL, CURLOPT_PROXY, $ip);
		curl_setopt($pCURL, CURLOPT_PROXYPORT, $port);
		curl_setopt_array($pCURL, $opt);
		$retorno = curl_exec($pCURL);
		$httpcode = curl_getinfo($pCURL, CURLINFO_HTTP_CODE);
		
		if(!curl_error($pCURL) && $httpcode == "200"){
			return json_encode(array("status" => 1, "return" => "200 Connection estabilished"));
		}else{
			return json_encode(array("status" => 0, "return" => curl_error($pCURL)));
		}
	}
	
	/*
	*@ Credit Card Generator / Gerador de Cartão de Crédito
	*@ Attemption: $bin and $length required!!!
	*@ Returns card generated.
	*/
	
	public function rndCreditCard($bin, $length) {
		if(!$bin){
			throw new Exception('Insert bin of CC.');
		}
		
		$ccnumber = $bin;

		while ( strlen($ccnumber) < ($length - 1) ) {
			$ccnumber .= rand(0,9);
		}

		$sum = 0;
		$pos = 0;
		$reversedCCnumber = strrev( $ccnumber );
		while ( $pos < $length - 1 ) {
			$odd = $reversedCCnumber[ $pos ] * 2;
			if ( $odd > 9 ) {
				$odd -= 9;
			}
			$sum += $odd;
			if ( $pos != ($length - 2) ) {
				$sum += $reversedCCnumber[ $pos +1 ];
			}
			$pos += 2;
		}

		$checkdigit = (( floor($sum/10) + 1) * 10 - $sum) % 10;
		$ccnumber .= $checkdigit;
		return $ccnumber;
	}
	
	/*
	*@ Credit Card Validator / Validador de Cartão de Crédito
	*@ Attemption: $cc required!!! and Credits of CreditCard Class it's from inacho.
	*@ Returns 1 = Valid or null = Invalid.
	*/
	
	public function validCC($cc){
		/* External class CreditCard, All rights reserved for inacho. */
		$validar = $this->validCreditCard($cc);
		return $validar[valid];
	}
	
}

$x = new Auxiliary();
echo $x->checkProxy("187.72.166.10:8080", "http");
?>