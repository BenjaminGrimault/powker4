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
     */
    public function insertPiece($piece, $gameId)
    {
        $return = true;

        // Find related game
        $game = $this->em
            ->getRepository('Powker4Bundle:Game')
            ->findOneById($gameId)
        ;
        // Get related grid
        $grid = $game->getGrid();

        // Check bounds
        if ($piece->getX() < 0 || $piece->getX() >= $grid->getX()) {
            $return = false;
        }

        // Find pieces are in same column than pieces
        $pieces = $this->em->getRepository('Powker4Bundle:Piece')
            ->retrievePiecesByColumn($piece->getX(), $grid);

        $newY = count($pieces);

        if ($return) {
            if ($newY >= $grid->getY()) {
                $return = false;
            }
        }

        if ($return) {
            $piece->setY($newY);
            $piece->setColor($this->getCurrentColor($grid->getId()));
            $piece->setGrid($grid);
            // \Doctrine\Common\Util\Debug::dump($piece);
            // die();
            $this->em->persist($piece);
            $this->em->flush();
        }

        return $return;
    }

    public function getCurrentColor($gridId)
    {
        $return = 0;

        $pieceRepo = $this->em->getRepository('Powker4Bundle:Piece');

        $colorsNumber = [];

        for ($i = 0; $i < $this->getPlayersNumber() ; $i++) {
            $colorsNumber[$i] = $pieceRepo
                ->findPiecesNumberByColor($gridId, $i);
        }

        $return = array_keys($colorsNumber, min($colorsNumber))[0];

        return $return;
    }

    public function getPlayersNumber()
    {
        return 2;
    }

    /**
     * @var Grid $grid
     * @return array
     */
    public function getGameBoard($grid)
    {
        $return = [];

        foreach ($grid->getPieces() as $piece) {
            $return[$piece->getX()][$grid->getY() - $piece->getY() - 1] =
                $piece->getColor();
        }

        return $return;
    }

}
