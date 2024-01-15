<?php

class Hand
{
    public $handName;
    public $cards = [];
    public $bet;
    public $stillPlaying = true;

    public function __construct(array $cards, $bet)
    {
        $this->cards = $cards;
        $this->bet = $bet;
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function doubleDown($card): void
    {
        $this->cards[] = $card;
        $this->bet *= 2;
        $this->stillPlaying = false;
    }
}