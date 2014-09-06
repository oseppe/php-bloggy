<?php //-->

class Page_User_Comments extends Page {
	protected $_title = 'Comments';
	protected $_template = '/user/comments.phtml';

	public function render() {
		$control = Control::getInstance();
		$variables = $control->getVariables();


		//mysql stuff
		$results = $control->store('user')->getDetails($variables[0]);

		$this->_body = array(
			'user' => $results,
			'id' => $variables[0]
		);

		return $this->page();
	}
}