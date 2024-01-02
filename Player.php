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

    public function showHand($check)
    {
        $out = '';
        foreach ($this->hand as $item) {
            if ($check == 'result') {
                $out .= $item->show() . ' ';
            } else {
                for ($x = 0; $x < 1; $x++) { 
                    $out .= $item->show();
                    break 2;
                }
            }
        }
        return "$this->name has $out";
    }

    public function showHandRaw()
    {
        return substr($this->showHand('result'), 10);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hand(): array
    {
        return $this->hand;
    }

    public function bet()
    {
        return $this->bet;
    }

    public function doubleDown()
    {
        $this->bet *= 2;
        $this->stillPlaying = false;
    }
}