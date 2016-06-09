<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Powker4Bundle\Entity\Game;
use Powker4Bundle\Entity\Grid;
use Powker4Bundle\Entity\Piece;
use Powker4Bundle\Form\GridType;
use Powker4Bundle\Form\PieceType;

class GameController extends Controller
{
    public function indexAction()
    {
        return $this->render('Powker4Bundle:Game:index.html.twig');
    }

    public function startAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formBuilder = new GridType();
        $grid = new Grid();

        $form = $this->createForm($formBuilder, $grid, [
            'method' => 'POST',
            'action' => $this->generateUrl('powker4_game_start'),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $game = new Grid();

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

    public function viewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $game = $em->getRepository('Powker4Bundle:Game')
            ->findOneById($id);

        $formBuilder = new PieceType();
        $piece = new Piece();

        $forms = [];

        for ($i = 0; $i < 7; $i++) {
            $form = $this->createForm($formBuilder, $piece, [
                'method' => 'POST',
                'action' => $this->generateUrl('powker4_game_update', [
                    'id' => $id,
                ]),
            ]);

            $forms[] = $form->createView();
        }

        $form->handleRequest($request);

        return $this->render('Powker4Bundle:Game:index.html.twig', [
            'game'  => $game,
            'forms' => $forms,
        ]);
    }

    public function updateAction($id)
    {

        return $this->redirect($this->generateUrl('powker4_game_view', [
            'id' => $id,
        ]));
    }

    public function finishAction($id)
    {
        $us = $this-getRepository('Powker4Bundle:Game')->findUs();
        return $this->redirect('powker4_index');
    }
}
