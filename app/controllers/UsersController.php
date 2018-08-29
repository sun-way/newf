<?php
class UsersController extends BaseController
{
    protected $modelName = 'User';
    protected $loginTemplate = 'adminLogin.twig';
    protected $template = 'adminUsers.twig';

    public function update($params)
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $thisModel = $this->getThisModel();
        $operation = !empty($params['id']) ? 'update' : 'create';
        $login = Request::get('name');
        $password = Request::get('password');
        $result = $thisModel->setUser($operation, $login, $password, $params['id']);
        $userName = $this->getThisModel()->getUserName();
        if ($result) {
            if (!empty($params['id'])) {
                $userId = $params['id'];
                $logMsg = "$userName обновил пользователя \"$login\" ($userId)";
            } else {
                $logMsg = "$userName создал пользователя \"$login\"";
            }
            Logger::getLogger('actions')->log($logMsg);
        }
        $this->index($params);
    }

  //  protected function getThisModel()
   // {
     //   return $this->model;
   // }

    public function index($params, $items = [])
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $thisModel = $this->getThisModel();
        $params = array_merge($params, $this->getNeedParams()); // добавляем требуемые для меню параметры
        $params['user'] = $thisModel->getUserName();
        $params['items'] = count($items) > 0 ? $items : $thisModel::all();
        if (!empty($params['id'])) {
            $params['editItem'] = $thisModel::find($params['id']);
        }
        $params['errors'] = Message::all();
        $this->render($this->template, $params);
    }

    public function postLogin($params)
    {
        if (isPost()) {
            $this->getThisModel()->checkForLogin(getParam('login'), getParam('password'));
            if (!empty(getParam('remember_me'))) {
                Session::Put('authLogin', getParam('login'));
                Session::Put('remember_me', 'checked');
            }
        }
        $this->getLogin($params);
    }

    public function getLogin($params)
    {
        $userName = $this->getThisModel()->getUserName();
        if (!empty($userName)) {
            $params['user'] = $userName;
            Router::redirect('index');
        }
        $params['authLogin'] = Session::Get('authLogin');
        $params['remember_me'] = Session::Get('remember_me');
        $params['errors'] = Message::all();
        $this->render($this->loginTemplate, $params);
    }

    public function getLogout()
    {
        $this->getThisModel()->logout();
    }
}
