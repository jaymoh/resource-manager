<?php

return [
    "data" => [
        '*' => [
            "id",
            "title",
            "post_type",
            "created_at",
            "updated_at",
            "relationships" => [
                "pdf",
                "link",
                "html"
            ]
        ]
    ],
    "links" => [
        "first",
        "last",
        "prev",
        "next"
    ],
    "meta" => [
        "current_page",
        "from",
        "last_page",
        "links",
        "path",
        "per_page",
        "to",
        "total"
    ]
];
