<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends AbstractController
{
    public function index(): RedirectResponse
    {
        return $this->redirect('https://sebastianluczak.github.io/php-roguelike/');
    }

    // TODO IDEA!!!
    // on each Tick of application serialize stateOfGameObject.
    // I wonder if that's even possible - save state of game as json object
    // and than restore it as even have full history (as diff even) as EventStore some-kind-of
    // TODO I really like this idea
    // google: - "how to create observer to check if class has changed (with its properties) php"
    public function test_route(): JsonResponse
    {
        return $this->json([
            'message' => 'Hello World',
            'version' => '0.0.1',
        ]);
    }
}
