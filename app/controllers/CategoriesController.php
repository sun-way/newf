<?php
class CategoriesController extends BaseController
{
    protected $modelName = 'Category';
    protected $template = 'adminCategories.twig';

    public function update($params)
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $userName = $this->getThisModel()->getUserName();
        if (!empty(getParam('name'))) {
            $name = trim(getParam('name'));
            $thisModel = $this->getThisModel();
            $operation = !empty($params['id']) ? 'update' : 'create';
            $result = $thisModel->setItem($operation, $name, $params['id']);
            if ($result) {
                if (!empty($params['id'])) {
                    $logMsg = "$userName обновил тему \"$name\" (" . $params['id'] . ')';
                } else {
                    $logMsg = "$userName создал тему \"$name\"";
                }
                Logger::getLogger('actions')->log($logMsg);
            }
        }
        $this->index($params);
    }

   // protected function getThisModel()
   // {
    //    return $this->model;
    //}

    public function index($params, $items = [])
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $thisModel = $this->getThisModel();
        $params = array_merge($params, $this->getNeedParams()); // добавляем требуемые для меню параметры
        $params['user'] = $thisModel->getUserName();
        if (!empty($params['id'])) {
            $params['editItem'] = $thisModel::find($params['id']);
        }
        $params['items'] = count($items) > 0 ? $items : $thisModel->getCategoriesList();
        $params['errors'] = Message::all();
        $this->render($this->template, $params);
    }
}