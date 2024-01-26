<?php

class Blackjack
{
    public function scoreHand(Hand $hand): string
    {
        $score = $this->points($hand);

        $outcome = [
            "BlackJack" => [
                'cards' => 2,
                'points' => 21
            ],
            "Twenty-One" => [
                'cards' => -1,
                'points' => 21
            ],
            "Five Card Charlie" => [
                'cards' => 5,
                'points' => -1,
            ]
        ];

        foreach ($outcome as $key => $value) {
            if (
                ($value['cards'] < 0 || $value['cards'] == count($hand->cards))
                && ($value['points'] < 0 || ($score <= 21 && $value['points'] == $score))
            ) {
                return $key;
            }
            if (count($hand->cards) == 2 && $score == 22) {
                return 'Two-Aces';
            }
        }
        return $score <= 21 ? (string)$score : 'is Busted';
    }



    public function points(Hand $hand): string
    {
        $score = 0;
        $haveAce = false;

        foreach ($hand->cards as $card) {
            $score += $card->score();

            if ($card->getValue() == 'A') {
                $haveAce = true;
            }
        }

        if ($haveAce == true && $score < 21) {
            return "$score/" . $score - 10;
        } 
        if ($haveAce == true && $score > 21) {
            $haveAce = false;
            return $score - 10;
        }
        return (string)$score;
    }

    public function splitCheck(Hand $hand)
    {
        if ($hand->cards[0]->getValue() === $hand->cards[1]->getValue()) {
            return true;
        }
        return false;
    }

    public function resultsValidation($players, $dealer): void
    {
        $info = [
            'WINNERS' => [],
            'TIED' => [],
            'LOSERS' => []
        ];
        $dealerPoints = $this->points($dealer->hands[0]);

        if ($dealerPoints > 21) {
            $this->bustedDealer($players, $info);
        }
        if ($dealerPoints == 21 || $this->scoreHand($dealer->hands[0]) == 'Five Card Charlie!') {
            $this->dealerWins($players, $dealer, $info);
        }
        if ($dealerPoints < 21) {
            $this->dealerBelow21($players, $dealer, $info);
        }
        $this->results($dealer, $players, $info);
    }

    private function bustedDealer($players, &$info)
    {
        foreach ($players as $player) {
            foreach ($player->hands as $hand) {
                $points = $this->points($hand);

                if ($points > 21) {
                    $info['LOSERS'][] = $hand;
                } else {
                    $info['WINNERS'][] = $hand;
                }
            }
        }
    }

    private function dealerWins($players, $dealer, &$info): void
    {
        foreach ($players as $player) {
            foreach ($player->hands as $hand) {
                $outputPlayer = $this->scoreHand($hand);

                switch ($this->scoreHand($dealer->hands[0])) {
                    case 'BlackJack':
                        if ($outputPlayer == 'BlackJack!') {
                            $info['TIED'][] = $hand;
                        } else {
                            $info['LOSERS'][] = $hand;
                        }
                        // BlackJack!
                    case 'Five Card Charlie':
                        if ($outputPlayer == 'BlackJack!') {
                            $info['WINNERS'][] = $hand;
                        } elseif ($outputPlayer == 'Five Card Charlie!') {
                            $info['TIED'][] = $hand;
                        } else {
                            $info['LOSERS'][] = $hand;
                        }
                        // Five-Card-Charlie
                    case 'Twenty-One':
                        if ($outputPlayer == 'BlackJack!' || $outputPlayer == 'Five Card Charlie!') {
                            $info['WINNERS'][] = $hand;
                        } elseif ($outputPlayer == 'Twenty-One!') {
                            $info['TIED'][] = $hand;
                        } else {
                            $info['LOSERS'][] = $hand;
                        }
                        // No default possible
                }
            }
        }
    }

    private function dealerBelow21($players, $dealer, &$info): void
    {
        foreach ($players as $player) {
            foreach ($player->hands as $hand) {
                $dealerPoints = $this->points($dealer->hands[0]);
                $playerPoints = $this->points($hand);

                if ($playerPoints > 21) {
                    $info['LOSERS'][] = $hand;
                } elseif ($playerPoints > $dealerPoints || $this->scoreHand($hand) == 'Five Card Charlie!') {
                    $info['WINNERS'][] = $hand;
                } elseif ($playerPoints < $dealerPoints) {
                    $info['LOSERS'][] = $hand;
                } else {
                    $info['TIED'][] = $hand;
                }
            }
        }
    }

    private function results($dealer, $players, $info): void
    {
        echo PHP_EOL . 'DEALER:' . PHP_EOL;
        echo 'Dealer has ' . $dealer->showHand($dealer->hands[0]) . '=> ' . $this->scoreHand($dealer->hands[0]) . PHP_EOL;

        echo PHP_EOL . 'SHOW HAND:' . PHP_EOL;
        foreach ($this->descResults($players) as $player) {
            echo $player['hand'] . '=> ' . $player['points'] . PHP_EOL;
        }

        foreach ($info as $key => $value) {
            if (empty($value)) {
                continue;
            } else {
                echo PHP_EOL . $key . ':' . PHP_EOL;
                foreach ($value as $result) {
                    if ($key == 'TIED') {
                        echo $result->handName . ' tied with Dealer!' . PHP_EOL;
                    } else {
                        echo $result->handName . PHP_EOL;
                    }
                }
            }
        }
        if (!empty($info['WINNERS']) || !empty($info['TIED'])) {
            echo PHP_EOL . 'BET:' . PHP_EOL;

            foreach ($info as $key => $value) {
                foreach ($value as $hand) {
                    $message = $this->scoreHand($hand);

                    if ($key == 'TIED') {
                        echo $hand->handName . ' has ' . $message . ' => ' . $hand->bet . ' X 1 => ' . $hand->bet . PHP_EOL;
                    } elseif ($key == 'WINNERS' && in_array($message, ['BlackJack', 'Five Card Charlie'])) {
                        echo $hand->handName . ' has ' . $message . ' => ' . $hand->bet . ' X 2.5 => ' . $hand->bet * 2.5 . PHP_EOL;
                    } elseif ($key == 'WINNERS') {
                        echo $hand->handName . ' has ' . $message . ' => ' . $hand->bet . ' X 2 => ' . $hand->bet * 2 . PHP_EOL;
                    }
                }
            }
        }
        die;
    }

    public function descResults($players): array
    {
        $mostPoints = [];
        foreach ($players as $player) {
            foreach ($player->hands as $hand) {
                $mostPoints[] = [
                    'hand' => $hand->handName . ' has ' . $player->showHand($hand),
                    'points' => $this->scoreHand($hand)
                ];
            }
        }

        usort($mostPoints, function ($a, $b) {
            return $a['points'] <=> $b['points'];
        });
        return $mostPoints;
    }
}
