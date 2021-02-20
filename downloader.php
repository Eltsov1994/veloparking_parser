<?php

class ControllerServiceDownloader extends Controller {
	public function index() {
      // set_time_limit(0);
      // exec ('wget -O novasport2.xml -x https://www.novasport.ru/yml/roznica.xml');
      // exec ('mv novasport2.xml');
      // if (file_get_contents('novasport.xml')){
      // 	include 'creat_xml.php';
      // }

      //include 'creat_xml.php';     

      $url = 'https://www.novasport.ru/yml/roznica.xml';
      $path = $_SERVER['DOCUMENT_ROOT'] . '/catalog/controller/service/novasport.xml';
      $get = file_get_contents($url);
      
      //Проверка 
      if ($get === false){ return; }

      $put = file_put_contents($path, $get);

      //Проверка 
      if ($put === false){ echo 'Ошибка'; return; }

      file_get_contents($this->url->link('service/creatxml'));
      file_get_contents($this->url->link('parser/parser'));
   }
}