<?php

namespace App\Controller;

use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{

    /**
     * @Route("/home", name="home",methods={"GET","POST"})
     */
    public function index(MainService $service): Response
    {
        $courses = $service->showCourses();

        return $this->render('index.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/search", name="search",methods={"GET"})
     */
    public function search(Request $request, MainService $service): Response
    {
        $query = $request->query->get('q');
        $results = $service->showResult($query);

        return $this->render('results.html.twig', [
            'results' => $results
        ]);
    }

}

