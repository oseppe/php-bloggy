<?php

class Page_Post extends Page{
	protected $_title = 'Posts';
	protected $_template = '/post.phtml';
	
	
	public function render() {
		session_start();
		
		$emptyMessage = 'Got no posts yet';		
		
		//$list = array();

		$list = eden('mysql', 'localhost', 'blog', 'root', '')
					->search('post')
					->innerJoinOn('user', 'post_user=user_id')
					->getRows();

		$this->_body = array(
			'rows' => $list,
			'emptyMessage' => $emptyMessage
		);

		return $this->page();
	}
}