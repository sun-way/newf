<?php
class LoggerController extends BaseController
{
    protected $modelName = 'Logger';
    protected $template = 'adminLog.twig';
    public function update($params)
    {
        $this->index($params);
    }

    public function index($params, $items = [])
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $thisModel = $this->getThisModel();
        $params = array_merge($params, $this->getNeedParams()); // добавляем требуемые для меню параметры
        $params['user'] = $thisModel->getUserName();
        $params['logs'] = $thisModel->getLogContents('actions');
        $params['errors'] = Message::all();
        $this->render($this->template, $params);
    }

    //protected function getThisModel()
    //{
     //   return $this->model;
    //}
}
