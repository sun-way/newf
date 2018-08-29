<?php
abstract  class BaseController
{
    protected $model = null;
    protected $modelName = 'BaseModel';
    protected $template;
    protected $errorTemplate = 'errorView.twig';

    public function __construct()
    {
        $modelFile = 'app/models/' . 'BaseModel' . '.php';
        if (is_file($modelFile)) {
            if (!class_exists($this->modelName)) {
                include $modelFile;
            }
            $this->model = new $this->modelName();
        } else {
            throw new Exception("Модель $modelFile не обнаружена");
        }
    }

    public function store($params)
    {
        if (Router::currentRouteName() !== 'index_store') {
            // незалогиненному пользователю разрешен только один маршрут - index_store
            $this->checkLogin();
        }
        /* если была нажата кнопка Добавить */
        if (Request::has('add')) {
            $this->update($params);
            die();
        }
        $this->index($params);
    }
    public function checkLogin()
    {

        if (empty($this->getThisModel()->getUserName())) {
            // если не залогинен
            Router::redirect('login');
        }
    }

    public function getThisModel()
    {
        return $this->model;
    }
    abstract public function update($params);
    abstract public function index($params, $items = []);

    public function create($params)
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $params['action'] = 'create';
        $this->index($params);
    }

    public function destroy($params)
    {
         $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $thisModelClass = $this->modelName;
        if (count($thisModelClass::all()) > 1) {
            $result = $thisModelClass::destroy($params['id']);
        } else {
            $result = false;
            $message = new Message('Ошибка удаления: нельзя удалять последний элемент.',
                Message::WARNING, 400);
        }

        $userName = $this->getThisModel()->getUserName();
        if ($result) {
            $message = new Message('Удаление успешно', Message::SUCCESS, 200);
            $itemId = $params['id'];
            switch ($this->modelName) {
                case 'Category':
                    $itemName = 'тему';
                    break;
                case 'User':
                    $itemName = 'пользователя';
                    break;
                case 'Question':
                    $itemName = 'вопрос';
                    break;
                default:
                    $itemName = 'элемент';
                    break;
            }
            $logMsg = "$userName удалил $itemName ($itemId)";
            Logger::getLogger('actions')->log($logMsg);
        } else {
            if (empty($message)) {
                $message = new Message('Ошибка удаления', Message::WARNING, 400);
            }
        }
        $message->save();
        $this->index($params);
    }

    public function edit($params)
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $params['action'] = 'edit';
        $this->index($params);
    }

    public function show($params)
    {
        $this->checkLogin(); // если не залогинен - переадресуем на страницу входа
        $thisModelClass = $params['BaseModel'];
        $items = $thisModelClass::find($params['id']);
        $this->index($params, $items);
    }

    protected function getNeedParams()
    {
        $params['categories'] = Category::getCategoriesList();
        $params['states'] = Question::getQuestionStateList();
        foreach ($params['states'] as $key => $state) {
            foreach ($params['categories'] as $category) {
                switch ($state) {
                    case Question::QUESTION_STATE_PUBLISHED;
                        $params['num_question_states'][$key] = (int)$params['num_question_states'][$key] + (int)$category['published_questions'];
                        break;
                    case Question::QUESTION_STATE_HIDDEN;
                        $params['num_question_states'][$key] = (int)$params['num_question_states'][$key] + (int)$category['hidden_questions'];
                        break;
                    case Question::QUESTION_STATE_WAIT_ANSWER;
                        $params['num_question_states'][$key] = (int)$params['num_question_states'][$key] + (int)$category['wait_answer_questions'];
                        break;
                    case Question::QUESTION_STATE_BLOCKED;
                        $params['num_question_states'][$key] = (int)$params['num_question_states'][$key] + (int)$category['blocked_questions'];
                        break;
                }
            }
        }
        foreach ($params['categories'] as $category) {
            $params['num_question_categories'][$category['id']] = array_sum([(int)$category['published_questions'],
                (int)$category['hidden_questions'], (int)$category['wait_answer_questions'],
                (int)$category['blocked_questions']]);
        }
        $params['num_questions'] = count(Question::all());
        return $params;
    }

    protected function render($template, $params = [])
    {
        // Где лежат шаблоны
        $loader = new Twig_Loader_Filesystem('app/views/');
        // Где будут хранится файлы кэша (php файлы)
        $twig = new Twig_Environment($loader, array(
            'cache' => 'app/storage/tmp/twig_cache',
            'auto_reload' => true,
        ));
        $twig->addFunction('staticCall', new Twig_Function_Function('staticCall'));
        try {
            echo $twig->render($template, $params);
            die;
        } catch (Exception $e) {
            Message::setCriticalErrorAndRedirect($e->getMessage(), $e->getCode());
        }
    }
}