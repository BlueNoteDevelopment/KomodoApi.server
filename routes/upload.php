<?php

$app->post("/api/upload", function ($request, $response, $arguments) {

    $data = "ok";

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
