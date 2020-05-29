<?php

namespace Anax\View;

?>

<h1><?= $question->getTitle() ?></h1>
<ul class="question-tag-list">
    <?php foreach ($question->getTags() as $t) { ?>
    <li class="question-tag">
        <a title="Click to browse all questions tagged '<?= $t->getName() ?>'" href="<?= url("questions?tag=" . \urlencode($t->getName())) ?>"><?= $t->getName() ?></a>
    </li>
    <?php }; ?>
    <?php if (empty($question->getTags())) { ?>
        <li class="question-tag">[No tags]</li>
    <?php }; ?>
</ul>

<div class="question-body">
    <?= $question->getBody() ?>
</div>
<a class="question-author" href="<?= url("users/view/" . $question->getAuthor()->getID()) ?>"><?= $question->getAuthor()->getUsername() ?></a>
<ul class="comments">
    <?php foreach ($question->getComments() as $c) { ?>
    <li class="comment" id="c<?= $c->getID() ?>">
        <p class="comment-body"><?= $c->getBody() ?></p>
        <a class="comment-author" href="<?= url("users/view/" . $c->getAuthor()->getID()) ?>"><?= $c->getAuthor()->getUsername() ?></a>
    </li>
    <?php }; ?>
</ul>
<form class="comment-form" method="POST" action="<?= url("comments/create/" . $question->getCommentContainerID()) ?>">
<input type="text" name="body" placeholder="Comment" required>
    <button type="submit">Submit</button>
</form>

<h2>Answers</h2>
<a class="call-to-action" href="<?= url("questions/answer/" . $question->getID()) ?>">Write an answer</a>
<ul class="answers">
    <?php foreach ($question->getAnswers() as $a) { ?>
    <li class="answer" id="a<?= $a->getID() ?>">
        <p class="answer-body"><?= $a->getBody() ?></p>
        <a class="answer-author" href="<?= url("users/view/" . $a->getAuthor()->getID()) ?>"><?= $a->getAuthor()->getUsername() ?></a>
        <ul class="comments">
            <?php foreach ($a->getComments() as $c) { ?>
            <li class="comment" id="c<?= $c->getID() ?>">
                <p class="comment-body"><?= $c->getBody() ?></p>
                <a class="comment-author" href="<?= url("users/view/" . $c->getAuthor()->getID()) ?>"><?= $c->getauthor()->getUsername() ?></a>
            </li>
            <?php }; ?>
        </ul>
        <form class="comment-form" method="POST" action="<?= url("comments/create/" . $a->getCommentContainerID()) ?>">
            <input type="text" name="body" placeholder="Comment" required>
            <button type="submit">Submit</button>
        </form>
    </li>
    <?php }; ?>
</ul>
