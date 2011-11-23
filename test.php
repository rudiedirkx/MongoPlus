<pre><?php

require_once 'MongoPlus.php';
require_once 'optional_helpers.php';

$_start = microtime(1);

$mongo = new MongoPlus;
#var_dump($mongo);
$db = $mongo->testdb;
#var_dump($db);
$collection = $db->testtable;
#var_dump($collection);


// start over

// drop table
$collection->drop();

// insert 5 random records
repeat(5, function() use ($collection) {
	$collection->insert(array(
		'name' => array(
			'first' => 'Voor' . rand(0, 999) . 'naamz',
			'last' => 'Lastest ' . rand(0, 999),
		),
		'email' => 'user-' . rand(0, 999) . '@onderstebuiten.nl',
		'some_number' => rand(0, 999),
	));
});


// query, alter & query new data

// show pre
$all = $collection->find()->limit(2);
#var_dump($all->count());
var_dump($all);
print_r($pre = iterator_to_array($all));

// do manual 'correct' update
if ( 0 ) {
	$id = key($pre);
	var_dump($id);
	var_dump($collection->update(array('_id' => new MongoId($id)), array('a' => 'b'), array('multiple' => false)));
}

// do updates
if ( 0 ) {
	$all = $collection->find()->limit(2);
	var_dump($all->update(function( $doc ) {
		return array(
			'$set' => array(
				'new_field1' => rand(0, 999),
				'some_number' => $doc['some_number'] + 3
			),
			'$addToSet' => array(
				'new_field2' => rand(0, 999)
			),
		);
	}));
}

// StackOverflow example code
if ( 1 ) {
	// query
	$docs = $collection->find()->limit(2);
	// fetch
	foreach ( $docs AS $id => $doc ) {
		// update
		$collection->update(array('_id' => $doc['_id']), array(
			'$set' => array(
				'some_number' => 'x',
				'new_field' => 'y',
			)
		), array('multiple' => false));
	}
}

// show post
$all = $collection->find()->limit(2);
print_r(iterator_to_array($all));



echo "\n" . number_format(microtime(1) - $_start, 4) . "\n";




