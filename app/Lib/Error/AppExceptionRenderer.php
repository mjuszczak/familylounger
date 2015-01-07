<?php

App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {

    private function render_wrapper($params){

	$this->controller->response->statusCode($params['status_code']);

	$this->controller->set(array(
		'code' => $params['status_code'],
		'title_for_layout' => $params['title'],
		'msg' => $params['msg']
	));
	
	$this->controller->layout = 'error';
	parent::_outputMessage($this->template);

    }

    public function authreqd($error) {
	$params = array(
		'status_code' => 403,
		'title' => 'Please login',
		'msg' => $error->getMessage()
	);

	$this->render_wrapper($params);
    }

    public function forbidden($error) {

	$params = array(
		'status_code' => 403,
		'title' => 'Forbidden',
		'msg' => $error->getMessage()
	);

	$this->render_wrapper($params);
    }

    public function notFound($error) {
	
	$params = array(
		'status_code' => 404,
		'title' => 'Not Found',
		'msg' => $error->getMessage()
	);
    }

    public function internalError($error) {

	$params = array(
		'status_code' => 500,
		'title' => 'Internal Error',
		'msg' => $error->getMessage()
	);
    }
}
