<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
}));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());	// démarre automatiquement la gestion des sessions PHP.
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',	//pattern définit la partie sécurisée de l'application sous la forme d'une expression rationnelle. Ici, la valeur ^/ indique que le pare-feu sécurise l'intégralité de l'application ;
            'anonymous' => true,	//anonymous précise qu'un utilisateur non authentifié peut tout de même accéder à la partie sécurisée. Il est nécessaire pour que les visiteurs anonymes puissent continuer à consulter les articles du CMS ;
            'logout' => true,	// logout indique qu'il est possible pour les utilisateurs authentifiés de se déconnecter de l'application 
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),	//form permet d'utiliser un formulaire comme méthode d'authentification.
            'users' => $app->share(function () use ($app) {		// users définit le fournisseur de données utilisateur, autrement dit la source de données qui permet d'accéder aux utilisateurs de l'application. Ici, il s'agit logiquement d'une instance de la classe UserDAO créée précédemment.
                return new MicroCMS\DAO\UserDAO($app['db']);
            }),
        ),
    ),
    // mise en place d'une hiérarchie entre les roles. ROLE_ADMIN contient ROLE_USER
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    // Création d'une zone protégée */admin accessible à ROLE_ADMIN uniquement
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

// Register services
$app['dao.article'] = $app->share(function ($app) {
    return new MicroCMS\DAO\ArticleDAO($app['db']);
});
$app['dao.user'] = $app->share(function ($app) {
    return new MicroCMS\DAO\UserDAO($app['db']);
});
$app['dao.comment'] = $app->share(function ($app) {
    $commentDAO = new MicroCMS\DAO\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setUserDAO($app['dao.user']);
    return $commentDAO;
});