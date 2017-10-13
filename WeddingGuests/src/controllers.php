<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WeddingGuests\Controller\GuestController;
use WeddingGuests\Repository\GuestRepository;

$app['guest.repository'] = function() use ($app) {
    return new GuestRepository($app['db']);
};

$app['guest.controller'] = function() use ($app) {
    return new GuestController($app['guest.repository']);
};

$app['naviation'] = [
    'home' => ['href' => '/', 'caption' => 'Home'],
    'add' => ['href' => '/guest/add', 'caption' => 'Gast toevoegen'],
    'list' => ['href' => '/guest/list', 'caption' => 'Gasten inzien'],
];

$app->match(
    '/guest/add',
    "guest.controller:addGuest")->method('GET|POST');

$app->get(
    '/guest/list',
    "guest.controller:viewGuests");

$app->get(
    '/guest/edit/{id}',
    "guest.controller:editGuest")->method('GET|POST');

$app->get(
    '/guest/delete/{id}',
    "guest.controller:deleteGuest");

$app->get(
    '/',
    function () use ($app) {
        $navigation = $app['naviation'];
        $navigation['home']['class'] = 'active';
        return $app['twig']->render(
            'index.html.twig',
            [
                'navigation' => $navigation
            ]);
    });

$app->error(
    function (\Exception $e, Request $request, $code) use ($app) {

        var_dump($e);die;
        // 404.html, or 40x.html, or 4xx.html, or error.html
        $templates = [
            'errors/' . $code . '.html.twig',
            'errors/' . substr($code, 0, 2) . 'x.html.twig',
            'errors/' . substr($code, 0, 1) . 'xx.html.twig',
            'errors/default.html.twig',
        ];

        return new Response(
            $app['twig']->resolveTemplate($templates)->render(['code' => $code]),
            $code);
    });
