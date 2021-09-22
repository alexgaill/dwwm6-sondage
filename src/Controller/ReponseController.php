<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Form\ReponseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReponseController extends AbstractController
{
    /**
     * @Route("/reponses", name="reponses")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", "Acces denied", "This user can't acces");
        $reponse = new Reponse;
        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setScore(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute("reponses");
        }

        return $this->render('reponse/index.html.twig', [
            "reponses" => $this->getDoctrine()->getRepository(Reponse::class)->findAll(),
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/reponse/{id}/update", name="reponse_update")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function update (Reponse $reponse, Request $request): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute("reponses");
        }

        return $this->render('reponse/update.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/reponse/{id}/{sondage}/up", name="reponse_up")
     *
     * @return Response
     */
    public function increment (Reponse $reponse, Sondage $sondage): Response
    {
        // La réponse que je récupère dans la BDD a un score que j'obtiens grâce à $response->getScore()

        $reponse->setScore( $reponse->getScore() +1 );
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("sondage_single", ["id" => $sondage->getId()]);
    }
}
