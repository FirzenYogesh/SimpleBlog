<?php

class BlogController extends Controller {
	public function actionIndex() {
		$posts = Post::model()->findAll();
		$data = array();
		foreach ($posts as $p) {
			$arr = array(
				'id' => $p->id,
				'author' => $p->author,
				'title' => $p->title,
				'category' => array(
					'category_id' => $p->category_id,
					//'category_name' => $p->category->name,
				),
			);
			array_push($data, $arr);
		}
		echo CJSON::encode($data);
	}

	public function actionViewPost($id) {
		$post = Post::model()->findByPk($id);
		$data = array(
			'id' => $post->id,
			'author' => $post->author,
			'title' => $post->title,
			'category' => array(
				'category_id' => $post->category->id,
				'category_name' => $post->category->name,
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
		$post = Post::create(array(
			"author" => $_POST['author'],
			"title" => $_POST['title'],
			"content" => $_POST['content'],
			"category_id" => $_POST['category_id'],
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
		$post = Post::model()->findByPk($id);
		if (isset($_POST['content'])) {
			$post->updateColumns(array(
				'content' => $_POST['content'],
			));
			if (!$post->hasErrors(NULL)) {
				$retstatus['statusCode'] = 1;
				$retstatus['statusMessage'] = "Update Content Successful";
			}
		}
		if (isset($_POST['category_id'])) {
			$post->updateColumns(array(
				'category_id' => $_POST['category_id'],
			));
			if (!$post->hasErrors(NULL)) {
				$retstatus['statusCode'] = 1;
				$retstatus['statusMessage'] = "Update Category Successful";
			}
		}
		if (isset($_POST['content']) && isset($_POST['category_id'])) {
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
		$post = Comment::create(array(
			"author" => $_POST['author'],
			"comment" => $_POST['comment'],
			"post_id" => $_POST['post_id'],
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
		$post = Comment::model()->findByPk($id);
		if (isset($_POST['comment'])) {
			$post->updateColumns(array(
				'comment' => $_POST['comment'],
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
		$post = Category::create(array(
			"name" => $_POST['name'],
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