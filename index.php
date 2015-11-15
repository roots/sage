<?php
$context['posts'] = Timber::get_posts();

Timber::render('templates/index.twig', $context);