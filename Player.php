<?php 

class Player
{
    public $name;
    public $bet;
    public $hands = [];
    public $stillPlaying = true;


    public function __construct($name, $bet)
    {
        $this->name = $name;
        $this->bet = $bet;
    }

    public function receiveCards(array $cards): void
    {
        $this->setHand($cards);
    }

    private function setHand($cards): void
    {
        $index = count($this->hands);
        $this->hands[$index] = new Hand($cards, $this->bet);
    }

    public function setHandName(): void
    {
        $index = count($this->hands);
        if ($index < 2) {
            $this->hands[0]->handName = $this->name;
        } else {
            foreach ($this->hands as $key => $hand) {
                $hand->handName = "$this->name: hand " . $key + 1;
            }
        }
    }

    public function playingHands(): array
    {
        return array_filter($this->hands, fn ($hand) => $hand->stillPlaying == true);
        $this->playerStillPlaying();
    }

    public function playerStillPlaying(): void
    {
        $handsPlaying = 0;
        $index = count($this->hands);
        for ($x = 0; $x < $index; $x++) {
            if ($this->hands[$x]->StillPlaying == false) {
                $handsPlaying += 1;
            }
        }

        if ($handsPlaying == $index) {
            $this->stillPlaying = false;
        }
    }

    public function dealerFirstCard(): string
    {
        $firstCard = '';
        foreach ($this->hands as $hand) {
            foreach ($hand->cards as $card) {
                $firstCard .= $card->show();
                break;
            }
        }
        return $firstCard;
    }

    public function showHand(Hand $hand): string
    {
        $out = '';
        foreach ($hand->cards as $card) {
            $out .= $card->show() . ' ';
        }
        return $out;
    }
}