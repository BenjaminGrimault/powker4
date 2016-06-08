<?php

namespace Powker4Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Powker4Bundle\Entity\Game;
use Powker4Bundle\Entity\Grid;
use Powker4Bundle\Form\GridType;

class GameController extends Controller
{
    public function indexAction()
    {
        return $this->render('Powker4Bundle:Game:index.html.twig');
    }

    public function startAction(Request $request)
    {
        $formBuilder = new GridType();
        $grid = new Grid();

        $form = $this->createForm($formBuilder, $grid, [
            'method' => 'POST',
            'action' => $this->generateUrl('powker4_game_start'),
        ]);
        // $form->add('submit', 'submit', [
        //     'label' => 'Commencer',
        // ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($grid);
            $em->flush();

            return $this->redirect($this->generateUrl('powker4_game_view', [
                'id'   => $grid->getId(),
            ]));
        }

        return $this->render('Powker4Bundle:Game:start.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $game = $em->getRepository('Powker4Bundle:Game')
            ->findOneById($id);

        // $formBuilder = new PlayType();
        // $piece = new Piece();
        //
        // $form = $this->createForm($formBuilder, $piece, [
        //     'method' => 'POST',
        //     'action' => $this->generateUrl('powker4_game_update'),
        // ]);

        return $this->render('Powker4Bundle:Game:index.html.twig', [
            'game' => $game,
            // 'form' => $form->createView(),
        ]);
    }

    public function updateAction($id)
    {
        $formBuilder = new PieceType();
        $piece = new Piece();

        $form = $this->createForm($formBuilder, $grid, [
            'method' => 'POST',
            'action' => $this->generateUrl('powker4_game_start'),
        ]);

        return $this->redirect($this->generateUrl('powker4_game_view', [
            'id' => $game->getId(),
        ]));
    }

    public function finishAction($id)
    {
        return $this->redirect('powker4_index');
    }
}
