<?php
/**
 * Forum.
 */
return [
    "routes" => [
        [
            "info" => "Forum pages.",
            "mount" => "questions",
            "handler" => "\EVB\Forum\Controllers\QuestionController",
        ],
        [
            "info" => "Tag browser.",
            "mount" => "tags",
            "handler" => "\EVB\Forum\Controllers\TagController",
        ],
        [
            "info" => "Comment actions.",
            "mount" => "comments",
            "handler" => "\EVB\Forum\Controllers\CommentController",
        ],
        [
            "info" => "Home page.",
            "mount" => "",
            "handler" => "\EVB\Forum\Controllers\HomeController"
        ]
    ]
];
