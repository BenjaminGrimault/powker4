<?php

namespace Powker4Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="Powker4Bundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="startTs", type="bigint")
     */
    private $startTs;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="winner", type="integer", nullable=true)
     */
    private $winner;

    /**
    * @var Grid
    *
    * @ORM\OneToOne(targetEntity="Grid")
    */
    private $grid;

    public function __construct()
    {
        $this->startTs = time();
        $this->status  = false;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startTs
     *
     * @param integer $startTs
     * @return Game
     */
    public function setStartTs($startTs)
    {
        $this->startTs = $startTs;

        return $this;
    }

    /**
     * Get startTs
     *
     * @return integer
     */
    public function getStartTs()
    {
        return $this->startTs;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Game
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set winner
     *
     * @param \stdClass $winner
     * @return Game
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return \stdClass
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set grid
     *
     * @param \Powker4Bundle\Entity\Grid $grid
     * @return Game
     */
    public function setGrid(\Powker4Bundle\Entity\Grid $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get grid
     *
     * @return \Powker4Bundle\Entity\Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }
}
