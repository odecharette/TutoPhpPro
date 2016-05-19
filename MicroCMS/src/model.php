<?php

// Return all articles
function getArticles() {
    $bdd = new PDO('mysql:host=localhost;dbname=microcms;charset=utf8', 'root', '');
    $articles = $bdd->query('select * from article order by id desc');
    return $articles;
}