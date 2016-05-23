<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;
use WebLinks\Domain\User;

// Home page
$app->get('/', function () use ($app) {
    $links = $app['dao.link']->findAll();
    return $app['twig']->render('index.html.twig', array('links' => $links));
})->bind('home');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

// Submit link
$app->match('/link/submit', function (Request $request) use ($app) {
	$linkFormView = null;
    $link = new Link();
    $user = $app['user'];
    $link->setUser($user);
   // var_dump($app['user']->getId());

    $linkForm = $app['form.factory']->create(new LinkType(), $link);
    $linkForm->handleRequest($request);
    if ($linkForm->isSubmitted() && $linkForm->isValid()) {
        $app['dao.link']->save($link);
        $app['session']->getFlashBag()->add('success', 'Your link was succesfully added.');
    }
    $linkFormView = $linkForm->createView();
    return $app['twig']->render('link_form.html.twig', array('link' => $link, 'linkForm' => $linkFormView));
})->bind('submit-link');

//Admin page
$app->get('/admin', function () use ($app) {
	$users = $app['dao.user']->findAll();
	$links = $app['dao.link']->findAll();
	return $app['twig']->render('admin.html.twig', array('users' => $users, 'links' => $links));
})->bind('admin');


/********************************************************************/
/****************************** API *********************************/
/********************************************************************/

// API : get all links
$app->get('/api/links', function() use ($app) {
    $links = $app['dao.link']->findAll();
    // Convert an array of objects into an array of associative arrays ($responseData)
    $responseData = array();
    foreach ($links as $link) {
        $responseData[] = array(
            'id' => $link->getId(),
            'title' => $link->getTitle(),
            'url' => $link->getUrl()
            );
    }
    // Create and return a JSON response
    return $app->json($responseData);
})->bind('api_links');

// API : get a link
$app->get('/api/link/{id}', function($id) use ($app) {
    $link = $app['dao.link']->find($id);
    // Convert an object into an associative array ($responseData)
    $responseData = array(
        'id' => $link->getId(),
        'title' => $link->getTitle(),
        'url' => $link->getUrl()
        );
    // Create and return a JSON response
    return $app->json($responseData);
})->bind('api_link');