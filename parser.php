<?
header('Content-Type: text/html; charset=UTF-8');
//include $_SERVER['DOCUMENT_ROOT'].'/libs/info.php';

//==============================================
$URLnovasport = 'novasport.xml';

$XML = new XMLReader;
$XML->open($URLnovasport);
$doc = new DOMDocument;

// $PARRENT_IDnovasport = '1057';
// $needlyBrands = ["BMX","STELS","MERIDA","FORWARD","SCOTT","FORMAT","BULLS","HARO","CUBE","STARK"];
// $parentId[] = trim($PARRENT_IDnovasport);


$needlyBrands = ["BMX","STELS","MERIDA","FORWARD","SCOTT","FORMAT","BULLS","HARO","CUBE","STARK"];
$parentId = ['1057','1423'];



//=====================ПАРСИНГ=========================

//--------------------------------------------------------------------
//--------------------------------------------------------------------
//Собираем всю информацию из XML и раскладываем в массивы

while($XML->read()) {
	if($XML->nodeType === XMLReader::ELEMENT) {

		//Собираем категории
		if($XML->localName === 'category') {
			$node = simplexml_import_dom($doc->importNode($XML->expand(), true));
			if ( isset($node['id']) && isset($node['parentId']) ){
				$category_default[] = array(
					'name' => trim($node),
					'parentId' => trim($node['parentId']),
					'id' => trim($node['id']),
				);

			}
		}

	}
}

//--------------------------------------------------------------------




//--------------------------------------------------------------------
//--------------------------------------------------------------------
//Собираем массив из дочерних parent_id относительно заданых категорий


//Уровень 1 с категориями (Велосипеды, Аксессуары для велосипедов)
foreach ($category_default as $default_value) {

	foreach ($parentId as $value) {

		if ($default_value['id'] === $value){

			$category[$value] = array(
				'name' => $default_value['name'],
				'parentId' => $default_value['parentId'],
				'id' => $default_value['id'],
			);

		}

	}

}


//Уровень 2 с категориями (Детский, Горный)
foreach ($category_default as $default_value) {

	foreach ($category as $key => $value) {

		if ($default_value['parentId'] === $value['id']){

			$category[$key]['data'][] = array(
				'name' => $default_value['name'],
				'parentId' => $default_value['parentId'],
				'id' => $default_value['id'],
			);

		}

	}

}





//echo "<pre>"; print_r($category); echo "<pre>";

foreach ($category as $value) {
	echo($value['name']. '<br><br>');
	foreach ($value['data'] as $valueJ) {
		echo('- ' .$valueJ['name']. '<br>');
	}
	echo('<br>');
}







//--------------------------------------------------------------------
//--------------------------------------------------------------------
//Собираем массив из дочерних parent_id относительно заданых категорий

function getChildId($XML, $doc, $parentId){
	while($XML->read()) {
		if($XML->nodeType === XMLReader::ELEMENT) {

			//Собираем массив из дочерних parent_id

			if($XML->localName === 'category') {
				$node = simplexml_import_dom($doc->importNode($XML->expand(), true));
				foreach ($parentId as $value) {
					if ($node['parentId'] == $value) {
						$parentIdNew[$value][] = trim($node['id']);
					}
				}
			}

		}
	}
	return $parentIdNew;
}

$parentIdNew = getChildId($XML, $doc, $parentId);
$parentIdNew2 = getChildId($XML, $doc, $parentId);
echo "<pre>"; print_r($parentIdNew2); echo "<pre>";

//--------------------------------------------------------------------



//--------------------------------------------------------------------
//--------------------------------------------------------------------
//Довляем id заданых категорий тоже

foreach ($parentId as $key => $value) {
	$parentIdNew[$value][] = $value;
}

//--------------------------------------------------------------------


foreach ($parentIdNew as $parent_key => $parent_value) {
	$parentIdNewTMP[] = getChildId($XML, $doc, $parent_value);

	// foreach ($parent_value as $parent_value_key => $parent_value_value) {
	// 	echo "<pre>"; var_dump($parent_value_value); echo "<pre>";
	// }
}

	//echo "<pre>"; var_dump($parentIdNewTMP); echo "<pre>";


// while($XML->read()) {
// 	if($XML->nodeType === XMLReader::ELEMENT) {

// 		//Собираем массив из дочерних parent_id

// 		if($XML->localName === 'category') {
// 			$node = simplexml_import_dom($doc->importNode($XML->expand(), true));
// 			foreach ($parentId as $value) {
// 				if ($node['parentId'] == $value) {
// 					$parentIdNew[$value][] = trim($node['id']);
// 				}
// 			}
// 		}


// 		if($XML->localName === 'offer') {
// 			$node = simplexml_import_dom($doc->importNode($XML->expand(), true));
// 			$OFFERnovasport[] = $node;
// 		}

// 		if($XML->localName === 'yml_catalog') {
// 			$node = simplexml_import_dom($doc->importNode($XML->expand(), true));
// 			$DATEnovasport = trim($node{'date'});
// 		}


// 	}
// }










foreach ($parentId as $key => $valueJ) {
	foreach ($OFFERnovasport as $valueI) {

		if ( $valueI->categoryId == $valueJ ) {
			$needly[$key][] = $valueI;

		}

	}
}



//Отсееваем только нужное
foreach ($needly as $valueI) {

	foreach ($valueI as $key => $valueJ) {
		//echo "<pre>"; print_r($valueJ->name); echo "<pre>";
	}

  //Все FATBIKE
	// foreach ($valueI->name as $valueK) {
	// 	if ( (stripos($valueK, 'fat')) !== false ) {
	// 		$needlyTMP[] = $valueI;
	// 		continue(2);
	// 	}
	// }

	//echo "<pre>"; print_r($valueI); echo "<pre>";

  //ПО НУЖНЫМ БРЕНДАМ
	// foreach ($valueI->param as $valueK) {
	// 	if ($valueK{'name'} == 'Бренд') {
	// 		foreach ($needlyBrands as $valueL) {
	// 			if ($valueK == $valueL) {
	// 				$needlyTMP[] = $valueI;
	// 			}
	// 		}
	// 	}
	// }

}

$needly = $needlyTMP;

echo "<pre>"; print_r($needly); echo "<pre>";




// foreach ($parentId as $valueJ) {
// 	foreach ($OFFERnovasport as $valueI) {

// 		if ( $valueI->categoryId == $valueJ ) {
// 			$needly[] = $valueI;
// 		}

// 	}
// }


// //Отсееваем только нужное
// foreach ($needly as $valueI) {

//   //Все FATBIKE
// 	foreach ($valueI->name as $valueK) {
// 		if ( (stripos($valueK, 'fat')) !== false ) {
// 			$needlyTMP[] = $valueI;
// 			continue(2);
// 		}
// 	}

//   //ПО НУЖНЫМ БРЕНДАМ
// 	foreach ($valueI->param as $valueK) {
// 		if ($valueK{'name'} == 'Бренд') {
// 			foreach ($needlyBrands as $valueL) {
// 				if ($valueK == $valueL) {
// 					$needlyTMP[] = $valueI;
// 				}
// 			}
// 		}
// 	}

// }

// $needly = $needlyTMP;

// //ВСЕ БРЕНДЫ
// foreach ($needly as $valueI) {
// 	foreach ($valueI->param as $valueJ) {
// 		if ($valueJ{'name'} == 'Бренд') {
// 			$brands[] = trim($valueJ);
// 		}
// 	}
// }

// //$brands = array_unique($brands);


// foreach ($needlyBrands as $valueI) {
// 	$key = 0;
// 	foreach ($needly as $valueJ) {
// 		foreach ($valueJ->param as $valueK) {
// 			if ($valueK{'name'} == 'Бренд') {
// 				if ( $valueI == trim($valueK) ) {
// 					$key++;
// 				}
// 			}
// 		}
// 	}
// 	$brandsCount[$valueI] = $key;
// }

//arsort($brandsCount);

//echo "<pre>"; print_r($needly); echo "<pre>";

?>