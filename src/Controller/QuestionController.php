<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="questions")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $question = new Question;
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute("questions");
        }

        return $this->render('question/index.html.twig', [
            "questions" => $this->getDoctrine()->getRepository(Question::class)->findAll(),
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/question/{id}/update", name="question_update")
     *
     * @return Response
     */
    public function update (Question $question, Request $request): Response
    {
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute("questions");
        }

        return $this->render('question/update.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
