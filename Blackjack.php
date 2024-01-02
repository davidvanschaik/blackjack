<?php 

require_once("./Player.php");

class Blackjack
{
    public $players = [];
    public function scoreHand(array $hand): string
    {
        $score = $this->points($hand);

        if (count($hand) == 2 && $score == 21) {
            return 'BlackJack!';
        } elseif (count($hand) <= 5 && $score == 21) {
            return "Twenty-One!";
        } elseif ($score > 21) {
            return "is Busted!";
        } elseif (count($hand) == 5 && $score < 21) {
            return "Five Card Charlie!";
        } else {
            return (string)$score;
        }
    } 

    public function points($hand): int
    {
        $score = 0;

        foreach ($hand as $item) {
            $score += $item->score();
        }
        return $score;
    }

    public function resultValidation($dealer, $players)
    {
        $info = [
            'WINNERS' => [],
            'TIED' => [],
            'LOSERS' => []
        ];
        $dealerPoints = $this->points($dealer->hand());

        if ($dealerPoints > 21) {
            $this->bustedDealer($players, $info);
        } elseif ($dealerPoints == 21 || $this->scoreHand($dealer->hand()) == 'Five Card Charlie!') {
            $this->tiedWith($dealer, $players, $info);
        } else {
            $this->dealerBelowTwentyOne($dealer, $players, $info);
        }
        $this->results($dealer, $players, $info);
    }

    private function bustedDealer($players, &$info)
    {
        foreach ($players as $player) {
            $playerPoints = $this->points($player->hand());

            if ($playerPoints > 21) {
                $info['LOSERS'][] = $player;
            } else {
                $info['WINNERS'][] = $player;
            }
        }
    }

    private function tiedWith($dealer, $players, &$info)
    {
        foreach ($players as $player) {
            $outputPlayer = $this->scoreHand($player->hand());
            $outputDealer = $this->scoreHand($dealer->hand());

            if ($outputDealer == 'BlackJack!') {
                if ($outputPlayer == 'BlackJack!' ) {
                    $info['TIED'][] = $player;
                } else {
                    $info['LOSERS'][] = $player;
                }
            } elseif ($outputDealer == 'Five Card Charlie!') {
                if ($outputPlayer == 'BlackJack!') {
                    $info['WINNERS'][] = $player;
                } elseif ($outputPlayer == 'Five Card Charlie!') {
                    $info['TIED'][] = $player;
                } else {
                    $info['LOSERS'][] = $player;
                }
            } elseif ($outputDealer == 'Twenty-One!') {
                if ($outputPlayer == 'BlackJack!' || $outputPlayer == 'Five Card Charlie!') {
                    $info['WINNERS'][] = $player;
                } elseif ($outputPlayer == 'Twenty-One!') {
                    $info['TIED'][] = $player;
                } else {
                    $info['LOSERS'][] = $player;
                }
            } else {
                $info['LOSERS'][] = $player;
            }
        }
    }

    private function dealerBelowTwentyOne($dealer, $players, &$info)
    {
        foreach ($players as $player) {
            $dealerPoints = $this->points($dealer->hand());
            $playerPoints = $this->points($player->hand());

            if ($playerPoints > 21) {
                $info['LOSERS'][] = $player;
            } elseif ($playerPoints > $dealerPoints || $this->scoreHand($player->hand()) == 'Five Card Charlie!') {
                $info['WINNERS'][] = $player;
            } elseif ($playerPoints < $dealerPoints) {
                $info['LOSERS'][] = $player;
            } else {
                $info['TIED'][] = $player;
            }
        }
    }

    private function results($dealer, $players, $info)
    {
        echo PHP_EOL . 'DEALER:' . PHP_EOL;
        echo $dealer->showHand('result') . '=> ' . $this->scoreHand($dealer->hand()) . PHP_EOL;

        echo PHP_EOL . 'SHOW HAND:' . PHP_EOL;
        foreach ($this->descResults($players) as $value) {
            echo $value['hand'] . ' => ' . $value['points'] . PHP_EOL;
        }

        foreach ($info as $key => $value) {
            if (empty($value)) {
                continue;
            } else {
                echo PHP_EOL . $key . ':' . PHP_EOL;
                foreach ($value as $result) {
                    if ($key == 'TIED') {
                        echo $result->name() . ' tied with Dealer!' . PHP_EOL;
                    } else {
                        echo $result->name() . PHP_EOL;
                    }
                }
            }
        }
        if (!empty($info['WINNERS']) || !empty($info['TIED']) ) {
            echo PHP_EOL . 'BET:' . PHP_EOL;

            foreach ($info as $key => $value) {
                foreach ($value as $player) {
                    $message = $this->scoreHand($player->hand());

                    if ($key == 'TIED') {
                        echo $player->name() . ' has ' . $message . ' => ' . $player->bet() . ' X 1 => ' . $player->bet() . PHP_EOL;
                    } elseif ($key == 'WINNERS' && $message == 'BlackJack!' || $message == 'Five Card Charlie!') {
                        echo $player->name() . ' has ' . $message . ' => ' . $player->bet() . ' X 2.5 => ' . $player->bet() * 2.5 . PHP_EOL;
                    } elseif ($key == 'WINNERS') {
                        echo $player->name() . ' has ' . $message . ' => ' . $player->bet() . ' X 2 => ' . $player->bet() * 2 . PHP_EOL;
                    }
                }
            }
        }
    }

    public function descResults($players): array
    {
        $mostPoints = [];
        foreach ($players as $info) {
            $mostPoints[] = [
                'hand' => $info->showHand('result'),
                'points' => $this->scoreHand($info->hand())
            ];
        }

        usort($mostPoints, function ($a, $b) {
            return $a['points'] <=> $b['points'];
        });
        return (array)$mostPoints;
    }

    public function splitCheck($hand)
    {
        $check = false;

        if ($hand[0]->getValue() === $hand[1]->getValue()) {
            $check = true;
        }
        return $check;
    }
}