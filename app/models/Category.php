<?php
class Category extends BaseModel
{
    protected static $dbTableName = 'php_categories';

    public static function destroy($id)
    {
        $id = (int)$id;
        $parentResult = parent::destroy($id);
        if (is_int($id) && $parentResult) {
            // Удаляем вопросы, которые были связаны с категорией
            $questionsTableName = Question::getDbTableName();
            $sql = "DELETE FROM $questionsTableName WHERE category_id = :id;";
            $statement = self::getDB()->prepare($sql);
            $statement->bindParam('id', $id);
            $result = $statement->execute();
        } else {
            $result = false;
        }
        return $result;
    }

    public static function getCategoriesList()
    {
        $tableName = self::$dbTableName;
        $publishedQuestions = Question::QUESTION_STATE_PUBLISHED;
        $waitAnswerQuestions = Question::QUESTION_STATE_WAIT_ANSWER;
        $hiddenQuestions = Question::QUESTION_STATE_HIDDEN;
        $blockedQuestions = Question::QUESTION_STATE_BLOCKED;
        $sql =
            "SELECT cat.id, cat.name, cat.created_at, cat.updated_at, cat.user_id,
                (SELECT count(*) FROM php_questions WHERE php_questions.category_id=cat.id) AS all_questions,
                (SELECT count(*) FROM php_questions WHERE php_questions.category_id=cat.id AND php_questions.state = :published_questions) AS published_questions,
                (SELECT count(*) FROM php_questions WHERE php_questions.category_id=cat.id AND php_questions.state = :wait_answer_questions) AS wait_answer_questions,
                (SELECT count(*) FROM php_questions WHERE php_questions.category_id=cat.id AND php_questions.state = :hidden_questions) AS hidden_questions,
                (SELECT count(*) FROM php_questions WHERE php_questions.category_id=cat.id AND php_questions.state = :blocked_questions) AS blocked_questions
            FROM $tableName AS cat
            GROUP BY cat.id
            ORDER BY name;";
        $statement = self::getDB()->prepare($sql);
        $statement->bindParam('published_questions', $publishedQuestions);
        $statement->bindParam('wait_answer_questions', $waitAnswerQuestions);
        $statement->bindParam('hidden_questions', $hiddenQuestions);
        $statement->bindParam('blocked_questions', $blockedQuestions);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setItem($operation, $name, $id = null)
    {
        $tableName = static::$dbTableName;
        switch ($operation) {
            case 'update';
                $operationHint = 'обновлена';
                if (!self::find($id)) {
                    $message = new Message("Категория не была $operationHint - нет такой категории",
                        Message::WARNING, 404);
                    $message->save();
                    return false;
                }
                $sql = "UPDATE $tableName 
                        SET name = :name, user_id = :userId, updated_at = NOW() 
                        WHERE id = :id LIMIT 1";
                break;
            default:
                $operationHint = 'добавлена';
                if (self::getItem($name)) {
                    $message = new Message("Категория не была $operationHint - уже есть такая категория",
                        Message::WARNING, 400);
                    $message->save();
                    return false;
                }
                $sql = "INSERT INTO $tableName (name, user_id, created_at, updated_at) 
                        VALUES (:name, :userId, NOW(), NOW())";
                break;
        }
        $statement = self::getDB()->prepare($sql);
        $statement->bindParam('name', $name);
        $statement->bindParam('userId', $this->getCurrentUser('id'));
        if (!empty($id) && $operation === 'update') {
            $statement->bindParam('id', $id);
        }
        $result = $statement->execute();
        if ($result) {
            $message = new Message(
                "Категория успешно $operationHint",
                Message::SUCCESS,
                200
            );
        } else {
            $message = new Message(
                "Категория не была $operationHint",
                Message::WARNING,
                400
            );
        }
        $message->save();
        return $result;
    }
}
