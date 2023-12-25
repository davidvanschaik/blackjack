<?php

require_once("./Dealer.php");

class Player
{
    private $hand = [];
    private $name;
    private $bet;
    public $stillPlaying = true;

    public function __construct($name, $bet)
    {
        $this->name = $name;
        $this->bet = $bet;
    }

    public function addCard(Card $card)
    {
        array_push($this->hand, $card);
    }

    public function showHand()
    {
        $out = '';
        foreach ($this->hand as $item) {
                $out .= $item->show() . ' ';
        }
        return "$this->name has $out";
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hand(): array
    {
        return $this->hand;
    }
}