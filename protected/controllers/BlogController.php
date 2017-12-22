<?php

class BlogController extends Controller
{
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->select = array('id','author','title','category_id');
		$posts=PostsTable::model()->findAll($criteria);
		$data = array();
		foreach ($posts as $p) 
		{
			$arr = array(
				'id' => $p->id,
				'author' => $p->author,
				'title' => $p->title,
				'category_id' => $p->category_id 
			);	
			array_push($data, $arr);
		}
		echo CJSON::encode($data);
	}

	public function actionPostView($id)
	{
		$post=PostsTable::model()->findByPk($id);
		$data = array(
			'id'=>$post->id,
			'author'=>$post->author,
			'title'=>$post->title,
			'category_id'=>$post->category_id
		);
		$data['comments'] = array();
		foreach ($post->comments as $c)
		{
			$arr = array(
				'id'=> $c->id,
				'comment'=> $c->comment,
				'author'=> $c->author
			);
			array_push($data['comments'],$arr);
		}
		echo CJSON::encode($data);
	}

	public function actionPostCreate()
	{
		$retstatus = array();
		$post = PostsTable::create(array(
				"author"=>$_GET['author'],
				"title"=>$_GET['title'],
				"content"=>$_GET['content'],
				"category_id"=>$_GET['category_id']
			));
		if($post->hasErrors(NULL))
		{
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = array();
			foreach ($post->getErrors() as $key => $value) {
				$arr = array($key => $value);
				array_push($retstatus['statusMessage'], $arr);
			}
		}
		else
		{
			
			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Blog Post Successful";
		}
		echo json_encode($retstatus);
		
	}

	public function actionCommentCreate()
	{
		$retstatus = array();
		$post = CommentsTable::create(array(
			"author"=>$_GET['author'],
			"comment"=>$_GET['comment'],
			"post_id"=>$_GET['post_id']
		));
		if($post->hasErrors(NULL))
		{
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = CJSON::encode($post->getErrors(NULL));
		}
		else
		{
			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Comment Post Successful";
		}
		echo json_encode($retstatus);
	}
}