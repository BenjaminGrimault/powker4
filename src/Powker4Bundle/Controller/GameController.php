<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Powker4Bundle\Entity\Game;

class GameController extends Controller
{
    public function indexAction()
    {
        return $this->render('Powker4Bundle:Game:index.html.twig');
    }

    public function startAction()
    {
        $em = $this->getDoctrine()->getManager();

        $game = new Game();

        $em->persist($game);

        $em->flush();

        return $this->redirect($this->generateUrl('powker4_game_view', [
            'id' => $game->getId(),
        ]));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $game = $em->getRepository('Powker4Bundle:Game')
            ->findOneById($id);

        return $this->render('Powker4Bundle:Game:index.html.twig', [
            'game' => $game,
        ]);
    }

    public function updateAction($id)
    {
        return $this->redirect($this->generateUrl('powker4_game_view', [
            'id' => $game->getId(),
        ]));
    }

    public function finishAction($id)
    {
        return $this->redirect('powker4_index');
    }
}
