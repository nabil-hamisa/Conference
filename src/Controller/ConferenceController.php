<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConferenceRepository;
use Twig\Environment;
use App\Entity\Conference;
use App\Repository\CommentRepository;

class ConferenceController extends AbstractController
{
    private $twig;
    public  function  __construct(Environment $twig)
    {
        $this->twig=$twig;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index( ConferenceRepository $conferenceRepository): Response
    {
        return new Response($this->twig->render('conference/index.html.twig', [

        ]));
    }

    /**
     * + * @Route("/conference/{id}", name="conference")
     * + */
    public function show(ConferenceRepository $conferenceRepository, Request $request,  Conference $conference, CommentRepository $commentRepository)
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);
        return new Response($this->twig->render('conference/show.html.twig',
            [
                'conference' => $conference,
                'comments' => $paginator,
                'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            ]
        ));
    }


    /*  return $this->render('conference/index.html.twig', [
        'controller_name' => 'ConferenceController',
    ]);
$greet='';
 if ($name= $request->query->get('hello')){
     $greet= sprintf('<h1>Hello ! %s</h1>',htmlspecialchars($name));
 }
 return new Response(<<<EOF
<html>
<body>
  $greet
 <img src="/images/under-construction.gif" />


</body>
</html>

EOF
 );*/


}
