<?php

$categories = array(
	array(
		"id" => 1,
		"title" => "Обувь",
		"children" => array(
			array(
				"id" => 2,
				"title" => "Ботинки",
				'children' => array(
					array('id' => 3, 'title' => 'Кожа'),
					array('id' => 4, 'title' => 'Текстиль'),
				),
			),
			array('id' => 5, 'title' => 'Кроссовки',),
		)
	),

	array(
		"id" => 6,
		"title" => "Спорт",
		'children' => array(
			array(
				'id' => 7,
				'title' => 'Мячи'
			)
		)
	),
);

function searchCategory(array $categories, int $id): string
{
	foreach($categories as $row){
		if($row['id'] == $id){
			return $row['title'];
		}
		if(isset($row['children'])){
			$result = searchCategory($row['children'], $id);
			if(!is_null($result)){
				return $result;
			}
		}
	}
};

$result = searchCategory($categories, 3);
var_dump($result);

?>