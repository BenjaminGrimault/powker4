<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Powker4Bundle\Entity\Game;
use Powker4Bundle\Entity\Grid;
use Powker4Bundle\Entity\Piece;
use Powker4Bundle\Form\GridType;
use Powker4Bundle\Form\PieceType;
use Powker4Bundle\Api\Game as GameService;

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
            $em->persist($grid);
            $em->flush();

            $game = new Game();
            $game->setGrid($grid);
            $em->persist($game);
            $em->flush();

            return $this->redirect($this->generateUrl('powker4_game_view', [
                'id'     => $game->getId(),
                'player' => 0,
            ]));
        }

        return $this->render('Powker4Bundle:Game:start.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function viewAction(Request $request, $id, $player)
    {
        $gameSrv = $this->get('powker4.game');

        $em = $this->getDoctrine()->getManager();

        $game = $em->getRepository('Powker4Bundle:Game')
            ->findOneById($id);

        $grid = $game->getGrid();

        $pieces = $gameSrv->getGameBoard($grid);

        $forms = [];

        for ($i = 0; $i < $grid->getX(); $i++) {
            $form = $this->createForm(new PieceType(), new Piece(), [
                'method' => 'POST',
                'action' => $this->generateUrl('powker4_game_update', [
                    'id'     => $id,
                    'player' => $player,
                ]),
            ]);

            $forms[] = $form->createView();
        }

        return $this->render('Powker4Bundle:Game:index.html.twig', [
            'game'      => $game,
            'grid'      => $grid,
            'forms'     => $forms,
            'pieces'    => $pieces,
            'player'    => $player,
            'toPlay'    => $gameSrv->getCurrentColor($grid->getId()) == $player,
            'friendUrl' => $this->generateUrl('powker4_game_view', [
                'id'     => $id,
                'player' => 1,
            ]),
        ]);
    }

    public function updateAction(Request $request, $id, $player)
    {
        $gameSrv = $this->get('powker4.game');

        $formBuilder = new PieceType();
        $piece = new Piece();

        $form = $this->createForm($formBuilder, $piece, [
            'method' => 'POST',
            'action' => $this->generateUrl('powker4_game_update', [
                'id' => $id,
                'player' => $player,
            ]),
        ]);

        $form->handleRequest($request);

        $gameSrv->insertPiece($piece, $id);

        return $this->redirect($this->generateUrl('powker4_game_view', [
            'id'     => $id,
            'player' => $player,
        ]));
    }

    public function finishAction($id)
    {
        $us = $this-getRepository('Powker4Bundle:Game')->findUs();
        return $this->redirect('powker4_index');
    }

    /**
     * Unused / Example
     */
    public function jsonAction()
    {
        $return = json_encode(['coucou']);

        return new Response($return, 200, [
            'Content-Type' => 'application/json',
        ]);

        $return = new JsonResponse();
        $return->headers->set('Content-Type', 'application/json');
        $return->setData(json_encode(['coucou']));
        return $return;
    }
}
