<?php


namespace App\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends Controller
{
    /**
     * @Route("/hello", name="hello_world")
     */
    public function hello()
    {
        return new JsonResponse('world');
    }
}
