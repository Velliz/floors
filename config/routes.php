<?php $routes = [
    "page" => [
        "select" => [
            "controller" => "select",
            "function" => "select",
            "accept" => [
                "GET"
            ]
        ],
        "exchange" => [
            "controller" => "service",
            "function" => "exchange",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "floors/callbacks" => [
            "controller" => "floors\\main",
            "function" => "callbacks",
            "accept" => [
                "GET",
                "POST",
                "PUT",
                "PATCH"
            ]
        ],
        "facebook/callbacks" => [
            "controller" => "facebook\\main",
            "function" => "callbacks",
            "accept" => [
                "GET",
                "POST",
                "PUT",
                "PATCH"
            ]
        ],
        "google/callbacks" => [
            "controller" => "google\\main",
            "function" => "callbacks",
            "accept" => [
                "GET",
                "POST",
                "PUT",
                "PATCH"
            ]
        ],
        "twitter/callbacks" => [
            "controller" => "twitter\\main",
            "function" => "callbacks",
            "accept" => [
                "GET",
                "POST",
                "PUT",
                "PATCH"
            ]
        ],
        "profile" => [
            "controller" => "main",
            "function" => "profile",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "beranda" => [
            "controller" => "admin",
            "function" => "beranda",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "admin/logout" => [
            "controller" => "admin",
            "function" => "userlogout",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "account/logout" => [
            "controller" => "account",
            "function" => "userlogout",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "tos" => [
            "controller" => "main",
            "function" => "tos",
            "accept" => [
                "GET"
            ]
        ],
        "policy" => [
            "controller" => "main",
            "function" => "policy",
            "accept" => [
                "GET"
            ]
        ],
        "" => [
            "controller" => "main",
            "function" => "main",
            "accept" => [
                "GET"
            ]
        ],
        "application/create" => [
            "controller" => "manage\\applications",
            "function" => "create",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "application/edit/{!}" => [
            "controller" => "manage\\applications",
            "function" => "edit",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "application/delete" => [
            "controller" => "manage\\applications",
            "function" => "delete",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "application/detail/{!}" => [
            "controller" => "manage\\applications",
            "function" => "detail",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "applications" => [
            "controller" => "manage\\applications",
            "function" => "main",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "broker/create/{!}" => [
            "controller" => "manage\\broker",
            "function" => "create",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "broker/edit/{!}" => [
            "controller" => "manage\\broker",
            "function" => "edit",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "broker/delete" => [
            "controller" => "manage\\broker",
            "function" => "delete",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "permissions/create/{!}" => [
            "controller" => "manage\\permissions",
            "function" => "create",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "permissions/edit/{!}" => [
            "controller" => "manage\\permissions",
            "function" => "edit",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "permissions/delete/{!}" => [
            "controller" => "manage\\permissions",
            "function" => "delete",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "user/create" => [
            "controller" => "manage\\users",
            "function" => "create",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "user/edit/{!}" => [
            "controller" => "manage\\users",
            "function" => "edit",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "user/delete/{!}" => [
            "controller" => "manage\\users",
            "function" => "delete",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "user/detail/{!}" => [
            "controller" => "manage\\users",
            "function" => "detail",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "users" => [
            "controller" => "manage\\users",
            "function" => "main",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "authorization/create/{!}" => [
            "controller" => "manage\\authorization",
            "function" => "create",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "authorization/delete/{!}" => [
            "controller" => "manage\\authorization",
            "function" => "delete",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "operators" => [
            "controller" => "manage\\operator",
            "function" => "main",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "settings" => [
            "controller" => "manage\\settings",
            "function" => "main",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "account/authorization" => [
            "controller" => "account",
            "function" => "authorization",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "account/history" => [
            "controller" => "account",
            "function" => "history",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "account" => [
            "controller" => "account",
            "function" => "profile",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "register" => [
            "controller" => "floors\\main",
            "function" => "register",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "recovery" => [
            "controller" => "floors\\main",
            "function" => "recovery",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "operator/detail/{!}" => [
            "controller" => "manage\\operator",
            "function" => "detail",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "operator/addnew" => [
            "controller" => "manage\\operator",
            "function" => "addnew",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/permission" => [
            "controller" => "api",
            "function" => "permission",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/authorization/{!}" => [
            "controller" => "api",
            "function" => "authorization",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/avatar/{!}/{!}" => [
            "controller" => "api",
            "function" => "avatar",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/uploadavatar/{!}" => [
            "controller" => "api",
            "function" => "upload_avatar",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/changeavatar/{!}/{!}" => [
            "controller" => "api",
            "function" => "change_avatar",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/credentialpic/{!}/{!}" => [
            "controller" => "api",
            "function" => "credential_picture",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/authorized" => [
            "controller" => "api",
            "function" => "authorized",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/user" => [
            "controller" => "api",
            "function" => "user",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/user/edit" => [
            "controller" => "api",
            "function" => "user_edit",
            "accept" => [
                "POST"
            ]
        ],
        "resume" => [
            "controller" => "resume",
            "function" => "main",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/confirm/password" => [
            "controller" => "api",
            "function" => "confirm_password",
            "accept" => [
                "GET",
                "POST"
            ]
        ],
        "api/login/info" => [
            "controller" => "api",
            "function" => "login_info",
            "accept" => [
                "POST"
            ]
        ],
        "api/credential/info" => [
            "controller" => "api",
            "function" => "credential_info",
            "accept" => [
                "POST"
            ]
        ],
        "api/list/users/{!}/{!}" => [
            "controller" => "api",
            "function" => "list_users",
            "accept" => [
                "POST",
                "GET"
            ]
        ],
        "setup" => [
            "controller" => "setup",
            "function" => "setup",
            "accept" => [
                "POST",
                "GET"
            ]
        ],
        "application/design/{!}" => [
            "controller" => "manage\\applications",
            "function" => "design",
            "accept" => [
                "GET",
                "POST"
            ]
        ]
    ],
    "error" => [
        "controller" => "main",
        "function" => "error",
        "accept" => [
            "GET"
        ]
    ],
    "not_found" => [
        "controller" => "main",
        "function" => "not_found",
        "accept" => [
            "GET"
        ]
    ]
]; return $routes;