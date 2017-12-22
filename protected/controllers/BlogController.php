<?php

class BlogController extends Controller
{
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->select = array('id','author','title','category_id');
		$posts=PostsTable::model()->findAll($criteria);
		echo CJSON::encode($posts);
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
		$post = new PostsTable;
		if($post->exists("title=:title",array(':title'=>$_GET['title'])))
		{
			$retstatus['statusCode'] = 0;
			$retstatus['statusMessage'] = "This Post already exists";
		}
		else
		{
			PostsTable::create(array(
				"author"=>$_GET['author'],
				"title"=>$_GET['title'],
				"content"=>$_GET['content'],
				"category_id"=>$_GET['category_id']
			));
			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Blog Post Successful";
		}
		echo json_encode($retstatus);
		
	}

	public function actionCommentCreate()
	{
		$retstatus = array();
		CommentsTable::create(array(
			"author"=>$_GET['author'],
			"comment"=>$_GET['comment'],
			"post_id"=>$_GET['post_id']
		));
		/*$comment = new CommentsTable;
		$comment->author = $_GET['author'];
		$comment->comment = $_GET['comment'];
		$comment->post_id = $_GET['post_id'];
		$comment->save();*/
		$retstatus['statusCode'] = 1;
		$retstatus['statusMessage'] = "Comment Post Successful";
		echo json_encode($retstatus);
	}
}