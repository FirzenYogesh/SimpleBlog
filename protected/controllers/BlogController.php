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
		echo CJSON::encode($post);
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
			$milliseconds = round(microtime(true) * 1000);
			$post->author = $_GET['author'];
			$post->title = $_GET['title'];
			$post->content = $_GET['content'];
			$post->category_id = $_GET['category_id'];
			$post->created_at = $milliseconds;
			$post->updated_at = $milliseconds;
			$post->save();
			$retstatus['statusCode'] = 1;
			$retstatus['statusMessage'] = "Blog Post Successful";
		}
		echo json_encode($retstatus);
		
	}

	public function actionCommentCreate()
	{
		$retstatus = array();
		$comment = new CommentsTable;
		$milliseconds = round(microtime(true) * 1000);
		$comment->author = $_GET['author'];
		$comment->comment = $_GET['comment'];
		$comment->post_id = $_GET['post_id'];
		$comment->created_at = $milliseconds;
		$comment->updated_at = $milliseconds;
		$comment->save();
		$retstatus['statusCode'] = 1;
		$retstatus['statusMessage'] = "Comment Post Successful";
		echo json_encode($retstatus);
	}
}