<?php

namespace EVB\Forum\Database;

use Anax\Database\Database;
use Anax\TextFilter\TextFilter;

use EVB\Forum\Models\Question;
use EVB\Forum\Models\User;
use EVB\Forum\Models\CommentContainer;

use EVB\Forum\Database\UserManager;
use EVB\Forum\Database\CommentManager;
use EVB\Forum\Database\AnswerManager;
use EVB\Forum\Database\TagManager;

class QuestionManager
{
    /**
     * Database connection
     *
     * @var Database
     */
    private $db;

    /**
     * User manager
     *
     * @var UserManager
     */
    private $userManager;

    /**
     * Comment manager
     *
     * @var CommentManager
     */
    private $commentManager;

    /**
     * Answer manager
     *
     * @var AnswerManager
     */
    private $answerManager;

    /**
     * Text filter
     *
     * @var TextFilter
     */
    private $textFilter;

    /**
     * Tag manager
     *
     * @var TagManager
     */
    private $tagManager;


    public function __construct(Database $db, UserManager $userManager, CommentManager $commentManager, AnswerManager $answerManager, TextFilter $textFilter, TagManager $tagManager) {
        $this->db = $db;
        $this->userManager = $userManager;
        $this->commentManager = $commentManager;
        $this->answerManager = $answerManager;
        $this->textFilter = $textFilter;
        $this->tagManager = $tagManager;
    }


    public function all() : array
    {
        $questions = $this->db->connect()->executeFetchAll("
            SELECT id, title, body, comment_container, author
              FROM questions
            ;
        ");

        foreach ($questions as $key => $q) {
            $questions[$key] = $this->fromDbData($q);
        }

        return $questions;
    }

    public function withTag(string $tag) : array
    {
        $questions = $this->db->connect()->executeFetchAll("
            SELECT q.id,
                   q.title,
                   q.body,
                   q.comment_container,
                   q.author
              FROM questions AS q
              JOIN questions_has_tags AS qt
                ON qt.question = q.id
              JOIN tags AS t
                ON t.id = qt.tag
             WHERE t.name = ?
            ;
        ", [$tag]);

        foreach ($questions as $key => $q) {
            $questions[$key] = $this->fromDbData($q);
        }

        return $questions;
    }

    public function byID(int $id) : Question
    {
        $question = $this->db->connect()->executeFetch("
            SELECT id, title, body, comment_container, author
              FROM questions
             WHERE id = ?
        ", [$id]);

        return $this->fromDbData($question);
    }

    public function create(string $title, string $body, int $author, array $tags) : int
    {
        $this->db->connect()->execute("
            INSERT INTO comment_containers()
            VALUES ()
            ;
        ");

        $commentContainer = $this->db->lastInsertId();

        $this->db->connect()->execute("
            INSERT INTO questions(title, body, comment_container, author)
            VALUES (?, ?, ?, ?)
            ;
        ", [
            $title,
            $body,
            $commentContainer,
            $author
        ]);

        $id = $this->db->lastInsertId();

        $this->tagManager->attach($id, $tags);

        return $id;
    }


    private function fromDbData(array $data) : Question
    {
        return new Question(
            $data["id"],
            $data["title"],
            $this->textFilter->markdown($data["body"]),
            $this->commentManager->byContainerID($data["comment_container"]),
            $this->userManager->byID($data["author"]),
            $this->answerManager->byQuestionID($data["id"]),
            $this->tagManager->byQuestionID($data["id"])
        );
    }
}
