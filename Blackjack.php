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
                $info['LOSERS'][] = $player->name();
            } else {
                $info['WINNERS'][] = $player->name();
            }
        }
    }

    private function tiedWith($players, &$info)
    {
        foreach ($players as $player) {
            if ($this->points($player->hand()) == 21) {
                $info['TIED'][] = $player->name() . ' tied with Dealer!';
            } else {
                $info['LOSERS'][] = $player->name();
            }
        }
    }

    private function dealerBelowTwentyOne($dealer, $players, &$info)
    {
        $dealerPoints = $this->points($dealer->hand());
        foreach ($players as $player) {
            $playerPoints = $this->points($player->hand());

            if ($playerPoints > 21) {
                $info['LOSERS'][] = $player->name();
            } elseif ($playerPoints > $dealerPoints) {
                $info['WINNERS'][] = $player->name();
            } elseif ($playerPoints < $dealerPoints) {
                $info['LOSERS'][] = $player->name();
            } else {
                $info['TIED'][] = $player->name() . ' tied with Dealer!';
            }
        }
    }

    private function results($dealer, $players, $info)
    {
        echo PHP_EOL . 'DEALER:' . PHP_EOL;
        echo $dealer->showHand() . '=> ' . $this->scoreHand($dealer->hand()) . PHP_EOL;

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
                    echo $result . PHP_EOL;
                }
            }
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