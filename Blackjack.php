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
            'LOSERS' => ['Dealer wins from:']
        ];

        if ($this->points($dealer->hand()) > 21) {
            foreach ($players as $player) {
                if ($this->points($player->hand()) <= 21) {
                    $info['WINNERS'][] = $player->name() . ' Wins!';
                }
            }
        } elseif ($this->points($dealer->hand()) == 21) {
            $win = $dealer->name() . ' Wins!';
            foreach ($players as $player) {
                if ($this->points($player->hand()) == $this->points($dealer->hand())) {
                     $info['TIED'][] = $dealer->name() . ' tied with ' . $player->name();
                } else {
                    $info['LOSERS'][] = $$player->name();
                }
            }
        } elseif ($this->points($dealer->hand()) < 21) {
            foreach ($players as $player) {
                if ($this->points($player->hand()) == 21) {
                    $info['WINNERS'][] = $player->name() . ' Wins!';
                } elseif ($this->points($player->hand()) < $this->points($dealer->hand())) {
                    $info['LOSERS'][] = $player->name();
                } elseif ($this->points($player->hand()) <= 21 && $this->points($player->hand()) > $this->points($dealer->hand())) {
                    $info['WINNERS'][] = $player->name() . ' Wins!';
                } elseif ($this->points($player->hand()) == $this->points($dealer->hand())) {
                    $info['TIED'][] = $dealer->name() . ' tied with ' . $player->name();
                } elseif ($this->points($player->hand()) > 21) {
                    $info['LOSERS'][] = $player->name();
                } elseif ($this->points($player->hand()) > 21 && count($player->hand()) > 4) {
                    $info['WINNERS'][] = $player->name() . ' Wins!';
                }
            }
        }
            echo PHP_EOL . 'DEALER:' . PHP_EOL;
            echo $dealer->showHand() . '=> ' . $this->scoreHand($dealer->hand()) . PHP_EOL;
            $this->finalResults($info, $players);
    }


    private function finalResults($info, $players)
    {
        foreach ($info as $key => $value) {
            if (empty($value)) {
                continue;
            } elseif ($key == 'LOSERS' && count($value) == 1) {
                echo PHP_EOL . $key . ':' . PHP_EOL;
                foreach ($value as $result) {
                    echo 'Dealer' . PHP_EOL;
                }
            } else {
                echo PHP_EOL . $key . ':' . PHP_EOL;
                foreach ($value as $result) {
                    echo $result . PHP_EOL;
                }
            }
        }

        echo PHP_EOL . 'SHOW HAND:' . PHP_EOL;
        foreach ($this->descResults($players) as $value) {
            echo $value['hand'] . ' => ' . $value['points'] . PHP_EOL;
        }
    }

    public function descResults($players): array
    {
        $mostPoints = [];
        foreach ($players as $info) {
            $mostPoints[] = [
                'hand' => $info->showHand(),
                'points' => $this->scoreHand($info->hand())
            ];
        }

        usort($mostPoints, function ($a, $b) {
            return $a['points'] <=> $b['points'];
        });
        return (array)$mostPoints;
    }
}