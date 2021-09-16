<?php

namespace App\Controller;

use App\Entity\Sondage;
use App\Form\SondageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SondageController extends AbstractController
{
    /**
     * @Route("/", name="sondages")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('sondage/index.html.twig', [
            'sondages' => $this->getDoctrine()->getRepository(Sondage::class)->findAll()
        ]);
    }

    /**
     * @Route("/sondage/new", name="sondage_new")
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $sondage = new Sondage;
        $form = $this->createForm(SondageType::class, $sondage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($sondage);
            $em->flush();

            return $this->redirectToRoute("sondages");
        }

        return $this->render("sondage/new.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/sondage/{id}", name="sondage_single")
     *
     * @return Response
     */
    public function single (Sondage $sondage): Response
    {
        $questions = $sondage->getQuestions();
        $best = array();
        foreach ($questions as $question) {
            $score= 0;
            $id = 0;
            foreach ($question->getReponses() as $reponse) {
                if ($reponse->getScore() > $score) {
                    $score = $reponse->getScore();
                    $id = $reponse->getId();
                }
            }
            $best[] = ["question_id" => $question->getId(), "reponse_id" => $id];
        }
        dump($best);
        return $this->render("sondage/single.html.twig", [
            "sondage" => $sondage,
            "best" => $best
        ]);
    }
}
