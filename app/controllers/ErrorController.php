<?php
namespace Phoenix\Controllers;
/**
 * ErrorController 
 */
class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        // $this->view->setTemplateAfter('error');
        $this->response->setHeader(404, 'Not Found');
        $this->loadCustomTrans('error');
        $this->view->pick('error/404');
    }

    public function showErrorAction()
    {
        // $this->view->setTemplateAfter('error');
        $this->response->setHeader(503, 'Error');
        $this->view->pick('error/503');
    }
}