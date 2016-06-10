<?php

namespace Powker4Bundle\Api;

class Game
{
    /**
     * @var EntityManager $em
     */
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @var Piece $piece
     * @var int $gameId
     * @var EntityManager $em
     * @throws Exception
     */
    public function insertPiece($piece, $gameId, $em)
    {
        // récupérer la game par son id via doctrine
        $game = $em
            ->getRepository('Powker4Bundle:Game')
            ->findOneById($gameId)
        ;
        // récupérer la grid par son id de game
        $grid = $game->getGrid();

        // vérifier que le x de la piece est correct (entre 0 et n)
        if ($piece->getX() < 0 || $piece->getX() >= $grid->getX()) {
            // Le jeton est en dehors de la grille ou null
            throw new Exception("Out of bounds", 1);
        }

        // Tous les jetons associés à la grid de la game en colonne x si la colonne n'est pas pleine

        $pieces = $em->getRepository('PieceRepository')
            ->retrievePiecesByColumn($piece->getX());

        // COmpter les jetons
        $number = count($piece);
        if ($number >= $grid->getY()) {
            throw new Exception("Out of bounds", 1);
        }
        $piece->setY($number++);


    }

    /**
     * @var Grid $grid
     * @return array
     */
    public function getGameBoard($grid)
    {
        $return = [];

        foreach ($grid->getPieces() as $piece) {
            $return[$piece->getX()][$grid->getY() - $piece->getY()] =
                $piece->getColor();
        }

        return $return;
    }

}
