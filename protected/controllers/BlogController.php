<?php

class BlogController extends Controller {
	public function actionIndex() {
		/*$criteria = new CDbCriteria;
		$criteria->select = array('id', 'author', 'title', 'category_id');*/
		$posts = PostsTable::model()->findAll();
		$data = array();
		foreach ($posts as $p) {
			$arr = array(
				'id' => $p->id,
				'author' => $p->author,
				'title' => $p->title,
				'category' => array(
					'category_id' => $p->category_id,
					//'category_name' => $p->category->category_name,
				),
			);
			array_push($data, $arr);
		}
		echo CJSON::encode($data);
	}

	public function actionViewPost($id) {
		$post = PostsTable::model()->findByPk($id);
		$data = array(
			'id' => $post->id,
			'author' => $post->author,
			'title' => $post->title,
			'category' => array(
				'category_id' => $post->category->id,
				'category_name' => $post->category->category_name,
			),
		);
		$data['comments'] = array();
		foreach ($post->comments as $c) {
			$arr = array(
				'id' => $c->id,
				'comment' => $c->comment,
				'author' => $c->author,
			);
			array_push($data['comments'], $arr);
		}
		echo CJSON::encode($data);
	}

	public function actionCreatePost() {
		$retstatus = array();
		$post = PostsTable::create(array(
			"author" => $_GET['author'],
			"title" => $_GET['title'],
			"content" => $_GET['content'],
			"category_id" => $_GET['category_id'],
		));
		if ($post->hasErrors(NULL)) {
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = array();
			foreach ($post->getErrors() as $key => $value) {
				$arr = array($key => $value);
				array_push($retstatus['statusMessage'], $arr);
			}
		} else {

			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Blog Post Successful";
		}
		echo json_encode($retstatus);

	}

	public function actionUpdatePost($id) {
		$retstatus = array();
		$post = PostsTable::model()->findByPk($id);
		if (isset($_GET['content'])) {
			$post->updateColumns(array(
				'content' => $_GET['content'],
			));
			if (!$post->hasErrors(NULL)) {
				$retstatus['statusCode'] = 1;
				$retstatus['statusMessage'] = "Update Content Successful";
			}
		}
		if (isset($_GET['category_id'])) {
			$post->updateColumns(array(
				'category_id' => $_GET['category_id'],
			));
			if (!$post->hasErrors(NULL)) {
				$retstatus['statusCode'] = 1;
				$retstatus['statusMessage'] = "Update Category Successful";
			}
		}
		if (isset($_GET['content']) && isset($_GET['category_id'])) {
			if (!$post->hasErrors(NULL)) {
				$retstatus['statusCode'] = 1;
				$retstatus['statusMessage'] = "Update Post Successful";
			}
		}
		if ($post->hasErrors(NULL)) {
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = CJSON::encode($post->getErrors(NULL));
		}
		echo json_encode($retstatus);
	}

	public function actionCreateComment() {
		$retstatus = array();
		$post = CommentsTable::create(array(
			"author" => $_GET['author'],
			"comment" => $_GET['comment'],
			"post_id" => $_GET['post_id'],
		));
		if ($post->hasErrors(NULL)) {
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = CJSON::encode($post->getErrors(NULL));
		} else {
			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Comment Post Successful";
		}
		echo json_encode($retstatus);
	}

	public function actionUpdateComment($id) {
		$retstatus = array();
		$post = CommentsTable::model()->findByPk($id);
		if (isset($_GET['comment'])) {
			$post->updateColumns(array(
				'comment' => $_GET['comment'],
			));
			if ($post->hasErrors(NULL)) {
				$retstatus['statusCode'] = 0;
				$retstatus['statusMessage'] = CJSON::encode($post->getErrors(NULL));
			} else {
				$retstatus['statusCode'] = 1;
				$retstatus['statusMessage'] = "Update Comment Successful";
			}
		} else {
			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "No Changes were given";
		}
		echo json_encode($retstatus);
	}

	public function actionAddCategory() {
		$retstatus = array();
		$post = CategoryTable::create(array(
			"category_name" => $_GET['category_name'],
		));
		if ($post->hasErrors(NULL)) {
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = array();
			foreach ($post->getErrors() as $key => $value) {
				$arr = array($key => $value);
				array_push($retstatus['statusMessage'], $arr);
			}
		} else {

			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Category Added Successful";
		}
		echo json_encode($retstatus);
	}
}