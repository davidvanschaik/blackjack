<?php 

class Dealer
{
    private Blackjack $BJ;
    private Deck $deck;
    private array $players = [];
    private $dealer;

    public function __construct(Blackjack $blackjack, Deck $deck)
    {
        $this->BJ = $blackjack;
        $this->deck = $deck;
    }

    public function addPlayers(Player $player): void
    {
        $this->players[] = $player;
    }

    public function drawCard(): Card
    {
        return $this->deck->drawCard();
    }

    public function dealCards(): void
    {
        foreach ($this->players as $player) {
            $cards = [];

            for ($x = 0; $x < 2; $x++) {
                $cards[] = $this->drawCard();
            }
            $player->receiveCards($cards);
        }
        $dealer = array_pop($this->players);
        $dealer->stillPlaying = false;
        $this->dealer = $dealer;
    }

    private function inTheGame(): array
    {
        return array_filter($this->players, fn ($player) => $player->stillPlaying == true);
    }

    private function dealerFirstCard(): string
    {
        return $this->dealer->name . ' has ' . $this->dealer->dealerFirstCard();
    }

    private function playerTurn(Player $player, $index): string
    {
        $turn = (count($player->hands) > 1) ? " => hand " . $index + 1 : "'s turn";
        return "$player->name$turn. You have " . $player->showHand($player->hands[$index]) . '=> ' . $this->BJ->scoreHand($player->hands[$index]);
    }

    public function playGame(): void
    {
        $playersStillPlaying = count($this->inTheGame());
        foreach ($this->inTheGame() as $player) {
            if (empty($player->playingHands())) {
                $playersStillPlaying--;
            }

            foreach ($player->playingHands() as $index => $hand) {
                echo PHP_EOL . "-----------------------------------" . PHP_EOL;
                echo PHP_EOL . $this->dealerFirstCard() . PHP_EOL;
                echo PHP_EOL . $this->playerTurn($player, $index) . PHP_EOL;
                $points = $this->BJ->points($hand);

                if (($points >= 21 && $this->BJ->splitCheck($hand) == false) || ($points < 21 && count($hand->cards) > 4)) {
                    $hand->stillPlaying = false;
                    continue;
                } else {
                    $this->makeChoice($hand, $player, $points);
                    break;
                }
            }
            $player->setHandName();
        }
        if ($playersStillPlaying === 0) {
            $this->dealerPlaying();
        }
    } 

    private function makeChoice(Hand $hand, Player $player, $points): void
    {
        while (true) {
            if ($this->BJ->splitCheck($hand) == true && count($hand->cards) == 2) {
                $choice = strtolower(readline("Hit (H), Double Down (D), Split (SP) or Stand (S) ?... "));

            } elseif ($points < 21 && count($hand->cards) == 2) {
                $choice = strtolower(readline("Hit (H), Double Down (D) or Stand (S) ?... "));

            } elseif ($points < 21 && count($hand->cards) > 2) {
                $choice = strtolower(readline("Hit (H) or Stand (S) ?... "));
            } 

            if (!str_contains('h d s sp', $choice)) {
                echo 'Input does not match any action, try again.' . PHP_EOL;
            } else {
                break;
            }

            if (($choice == 'sp' && $this->BJ->splitCheck($hand) == false) || ($choice == 'd' && count($hand->cards) > 2)) {
                echo 'Action does not match cards, try again.';
            }
        }
        if ($choice == 's') {
            $hand->stillPlaying = false;
            echo $player->name . ' Stands!' . PHP_EOL;
            $this->playGame();
        } else {
            $this->stillPlaying($hand, $player, $choice);
        }
    }

    private function stillPlaying(Hand $hand, Player $player, $choice): void
    {

        if ($choice == 'sp') {
            echo $player->name . ' Splits!' . PHP_EOL;
            $player->receiveCards([array_pop($hand->cards), $this->drawCard()]);
            $hand->addCard($this->drawCard());
            $this->playGame();
        }
        if ($choice == 'd') {
            echo $player->name . ' Double Downs!' . PHP_EOL;
            $hand->doubleDown($this->drawCard());
            $this->playGame();
        } else {
            $hand->addCard($card = $this->drawCard());
            echo $player->name . ' got ' . $card->show() . ' => ' . $player->showHand($hand) . '=> ' . $this->BJ->scoreHand($hand) . PHP_EOL;
            $points = $this->BJ->points($hand);

            if ($points < 21 && count($hand->cards) < 5) {
                $this->makeChoice($hand, $player, $points);
            } else {
                $hand->stillPlaying = false;
                $this->playGame();
            }
        }
    }

    private function dealerPlaying(): void
    {
        $dealer = $this->dealer;
        $hand = $dealer->hands[0];
        echo PHP_EOL . "-----------------------------------" . PHP_EOL;
        echo PHP_EOL . $dealer->name . "'s turn. " . $dealer->name . ' has ' . $dealer->showHand($hand) . '=> ' . $this->BJ->scoreHand($hand) . PHP_EOL;

        $dealerPlaying = true;
        while ($dealerPlaying) {
            $points = $this->BJ->points($hand);
            if ($points > 17 || $points < 21 && count($hand->cards) > 4) {
                echo $dealer->name . ' stops.' . PHP_EOL;
                $dealerPlaying = false;
            } else {
                sleep(1);
                $hand->addCard($card = $this->drawCard());
                echo $dealer->name . ' got ' . $card->show() . ' => ' . $dealer->showHand($hand) . '=> ' . $this->BJ->scoreHand($hand) . PHP_EOL;
            }
        }
        $this->BJ->resultsValidation($this->players, $dealer);
    }
}

