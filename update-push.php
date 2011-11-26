<pre><?php

require_once 'MongoPlus.php';
require_once 'optional_helpers.php';

$_start = microtime(1);

$mongo = new MongoPlus;
$db = $mongo->testdb;
$posts = $db->posts;

if ( isset($_GET['list']) ) {
	print_r(iterator_to_array($posts->find()));
}

if ( isset($_GET['insert']) ) {
	var_dump($posts->insert(array(
		'user' => 'Jaap',
		'created_on' => new DateTime,
		'tags' => array('x', 'y', 'z'),
		'body' => "Oele bla.\n\nBody in Markdown format or something.",
		'comments' => array(),
	)));
}

if ( isset($_GET['push_comment']) ) {
	// get random post
	$post = $posts->find()->limit( -1 )->skip(mt_rand( 0, $posts->count()-1 ))->getNext();
print_r($post);

	// create comment
	$comment = new Comment('Anonymous', 'ano'.rand(0, 99).'@example.com', "This is a comment.\n\nIn Markdown format! **Heeaalzz Yeeaah!**");
print_r($comment);

	// add comment
	var_dump($posts->update(
		array('_id' => $post['_id']),
		array(
			'$push' => array(
				'comments' => $comment,
			),
		),
		array() // options
	));
}



echo "<a href='?list'>list</a>\n";
echo "<a href='?insert'>insert</a>\n";
echo "<a href='?push+comment'>push comment</a>\n";



class Comment {
	public $user = '';
	public $email = '';
	public $comment = '';
	public $created_at = '';
	public function __construct( $user, $email, $comment ) {
		$this->user = $user;
		$this->email = $email;
		$this->comment = $comment;
		$this->created_at = new DateTime;
	}
}


