<?

class ControllerServiceCreatxml extends Controller {
	public function index() {

    $parser = $this->load->controller('service/parser');
    $DATEnovasport = $parser['$DATEnovasport'];
    $needlyBrands = $parser['$needlyBrands'];
    $brandsCount = $parser['$brandsCount'];
    $needly = $parser['$needly'];

    // Sales
    // $salesTmp = $this->db->query('SELECT * FROM oc_vpcatalog_sales')->rows;
    // $sales = [];
    // for ($i = 0; $i < count($salesTmp); $i++) {
    //   $sales[$i] = ['vendorCode' => $salesTmp[$i]['article'], 'procentSale' => $salesTmp[$i]['sale']]; 
		// }

    $exception = [ 
			['Шоссейные Велосипеды', 'Шоссейный'], 
			['Ободные (V-Brake)', 'V-brake (ободные)'], 
			['Ободные Механические', 'V-brake (ободные)'],
			['Ручной', 'V-brake (ободные)'],
			['Ободной (V-Brake)', 'V-brake (ободные)'],
			['V-Типа', 'V-brake (ободные)'],
			['Дисковые (Гидравл)', 'Дисковые гидравлические'], 
			['Передний Ручной, Задний Ножной', 'Ножной тормоз'], 
			['Ножные педальные', 'Ножной тормоз'], 
			['Ножной тормоз + ободные', 'Ножной тормоз'], 
			['Дисковые', 'Дисковые механические'], 
			['26 Дюймов', '26'], 
    ];
    
    if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

    //===========================================================================================

    $_name = "ВелоParking";
    $_company = "ВелоParking";
    $_url = $server;
    $_deliveryCost = 200;
    $_deliveryDays = 2;
    $_sale = 0.5;
    $_urlProduct = "?route=veloparking/product&amp;id=";
    //$_urlProduct = "?route=veloparking/product&id=";

    $dom = new domDocument("1.0", "UTF-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
    $dom->formatOutput = true;

    //Создаем оболочку CATALOG
    $catalog = $dom->createElement("yml_catalog");
    $dom->appendChild($catalog);
    //$catalog->setAttribute("date", date("Y-m-d H:i"));
    $catalog->setAttribute("date", $DATEnovasport);

    //Создаем оболочку CATALOG -> SHOP
    $shop = $dom->createElement("shop");
    $catalog->appendChild($shop);



    //Создаем оболочку CATALOG -> SHOP -> NAME
    $name = $dom->createElement("name", $_name);
    $shop->appendChild($name);

    //Создаем оболочку CATALOG -> SHOP -> COMPANY
    $company = $dom->createElement("company", $_company);
    $shop->appendChild($company);

    //Создаем оболочку CATALOG -> SHOP -> URL
    $url = $dom->createElement("url", $_url);
    $shop->appendChild($url);

    //Создаем оболочку CATALOG -> SHOP -> CURRENCIES
    $currencies = $dom->createElement("currencies");
    $shop->appendChild($currencies);

    //Создаем оболочку CATALOG -> SHOP -> CURRENCIES -> CURRENCY
    $currency = $dom->createElement("currency");
    $currencies->appendChild($currency);
    $currency->setAttribute("id", "RUR");
    $currency->setAttribute("rate", "1");

    //Создаем оболочку CATALOG -> SHOP -> CATEGORIES
    $categories = $dom->createElement("categories");
    $shop->appendChild($categories);

    //Создаем оболочку CATALOG -> SHOP -> CATEGORIES - > CATEGORY
    $category  = $dom->createElement("category", "Велосипеды");
    $categories->appendChild($category);
    $category->setAttribute("id", "100");

    //Создаем оболочку CATALOG -> SHOP -> CATEGORIES - > БРЕНДЫ
    $key = 0;
    foreach ($needlyBrands as $value) {
      $id = $key + 101;
      $pid = 100;
      $src_img = 'catalog/categories/';

      $category  = $dom->createElement("category", $value);
      $category->setAttribute("id", $id);
      $category->setAttribute("parentId", $pid);
      $category->setAttribute("img", ($src_img.$value.".jpg"));
      foreach ($brandsCount as $keyB => $valueJ) {
        if ( $value == $keyB) {
          $category->setAttribute("count", $valueJ);
        }
      }
      //$category->setAttribute("count", );
      $categories->appendChild($category);

      $key++;
    }

    //Создаем оболочку CATALOG -> SHOP -> DELIVERY-OPTIONS
    $delivery_options = $dom->createElement("delivery-options");
    $shop->appendChild($delivery_options);

    //Создаем оболочку CATALOG -> SHOP -> DELIVERY-OPTIONS -> OPTION
    $option  = $dom->createElement("option");
    $delivery_options->appendChild($option);
    $option->setAttribute("cost", $_deliveryCost);
    $option->setAttribute("days", $_deliveryDays);

    //Создаем оболочку CATALOG -> SHOP -> OFFERS
    $offers = $dom->createElement("offers");
    $shop->appendChild($offers);

    //Создаем оболочку CATALOG -> SHOP -> OFFERS -> OFFER
    $key = 0;
    foreach ($needlyBrands as $valueI) {
      $pid = $key + 101;

      foreach ($needly as $valueJ) {

        $id = $valueJ['id'];
        $_vendorCode = $valueJ->vendorCode;
        $_urlProductUnity = $_url.$_urlProduct.$_vendorCode;
        $_name = $valueJ->name;
        $_picture = str_replace('https', 'http', $valueJ->picture);
        $_description = htmlspecialchars($valueJ->description);


        foreach ($valueJ->param as $valueK) {
        if ($valueK{'name'} == 'Бренд') {
          if ($valueK == $valueI) {

            //Создаем оболочку CATALOG -> SHOP -> OFFERS -> OFFER
            $offer  = $dom->createElement("offer");
            $offers->appendChild($offer);
            $offer->setAttribute("id", $id);

            //Создаем оболочку CATALOG -> SHOP -> OFFERS -> OFFER[PARAMS]
            $vendorCode  = $dom->createElement("vendorCode", $_vendorCode);
            $offer->appendChild($vendorCode);

            $url  = $dom->createElement("url", $_urlProductUnity);
            $offer->appendChild($url);

            $name  = $dom->createElement("name", $_name);
            $offer->appendChild($name);

            $categoryId  = $dom->createElement("categoryId", $pid);
            $offer->appendChild($categoryId);

            for ($i=0; count($valueJ->{'size-'.(string) $i}) > 0 ; $i++) { 
              $size  = $dom->createElement("size-".(string) $i, $valueJ->{'size-'.(string) $i});
              $offer->appendChild($size);
            }
            
            // foreach ($sales as $saleProduct){
            //   if ($saleProduct['vendorCode'] == $vendorCode->{'textContent'}){

            //     for ($i=0; count($valueJ->{'price-'.(string) $i}) > 0 ; $i++) { 
            //       $priceItem = (int)($valueJ->{'price-'.(string) $i});
            //       $priceItemEdit = ceil ($priceItem - ( ($priceItem / 100) * $_sale ) );
            //       $priceItemEdit = ceil ($priceItemEdit / 10) * 10;
            //       $oldprice  = $dom->createElement("oldprice-".(string) $i, $priceItemEdit);
            //       $offer->appendChild($oldprice);

            //       $newpriceEdit = $priceItemEdit * ((100 - (int)($saleProduct['procentSale'])) / 100);
            //       $newpriceEdit = floor ($newpriceEdit / 10) * 10;
            //       $newprice  = $dom->createElement("price-".(string) $i, $newpriceEdit);
            //       $offer->appendChild($newprice);
            //     }

            //     goto skip;
            //   } 
            // }

            for ($i=0; count($valueJ->{'price-'.(string) $i}) > 0 ; $i++) { 
                  $priceItem = (int)($valueJ->{'price-'.(string) $i});
                  $priceItemEdit = ceil ($priceItem - ( ($priceItem / 100) * $_sale ) );
                  $priceItemEdit = ceil ($priceItemEdit / 10) * 10;
                  $price  = $dom->createElement("price-".(string) $i, $priceItemEdit);
                  $offer->appendChild($price);
            }

            skip:

            $picture  = $dom->createElement("picture", $_picture);
            $offer->appendChild($picture);

            $description  = $dom->createElement("description", $_description);
            $offer->appendChild($description);

            foreach ($valueJ->param as $valueL) {
              if ( ($valueL{"name"} != 'Свободно') && ($valueL{"name"} != 'Свободно под заказ') ){
                foreach ($exception as $valueH) {
                  if ( mb_strtoupper($valueL) == mb_strtoupper($valueH[0]) ) {
                    $param  = $dom->createElement("param", htmlspecialchars( $valueH[1] ));
                    $param->setAttribute("name", $valueL{"name"});
                    $offer->appendChild($param);
                    //echo "<pre>"; print_r($valueL); echo "<pre>";
                    continue(2);
                  }
                }
                $param  = $dom->createElement("param", htmlspecialchars( $valueL ));
                $param->setAttribute("name", $valueL{"name"});
                $offer->appendChild($param);
              }
            }

          }
        }
      }
    }
    $key++;
    }


    //$dom->save("bicycle.xml"); // Сохраняем полученный XML-документ в файл
    $dom->save($_SERVER['DOCUMENT_ROOT'] . '/catalog/controller/service/bicycle.xml'); // Сохраняем полученный XML-документ в файл

  }
}
