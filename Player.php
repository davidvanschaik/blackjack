<?php

require_once("./Dealer.php");

class Player
{
    private $hand = [];
    private $name;
    public $stillPlaying = true;

    public function __construct($name)
    {
        $this->name = $name;
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