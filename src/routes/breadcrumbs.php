<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Home
 */
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

/**
 * Home > [Post]
 */
Breadcrumbs::for('post', function (BreadcrumbTrail $trail, $post) {
    $trail->parent('home')->push($post->title, route('post.show', $post));
});


// use Diglactic\Breadcrumbs\Breadcrumbs;
// use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// /**
//  * Home
//  */
// Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
//     $trail->push('Home', route('home'));
// });

// /**
//  * Home > [Post]
//  */
// Breadcrumbs::for('post', function (BreadcrumbTrail $trail, $post) {
//     $trail->parent('home')->push($post->title, route('posts.show', $post));

// });

// /**
//  * Home > [Page]
//  */
// Breadcrumbs::for('page', function (BreadcrumbTrail $trail, $page) {
//     $trail->parent('home')->push($page->title, route('page', $page));
// });
