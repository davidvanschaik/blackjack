<?php

require_once("./Dealer.php");
require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");
require_once("./Card.php");
require_once("./Hand.php");

$dealer = new Dealer(new Blackjack(), new Deck(6));
$name = ['Jennifer', 'Foivos', 'Cesco', 'Nando', 'Nique', 'Romano', 'Milo', 'Gaby', 'Eugene', 'Jose', 'Sarah',
'Luuk', 'Kian', 'Stefan', 'Iris', 'KlangKuenstler', 'Kobosil', 'Oguz', 'Joris'];


while (true) {
    $AutoPlay = readline("Do you want to play with friends or autoplay? (friends - auto) => .. ");
    if ($AutoPlay == 'auto' || $AutoPlay == 'friends') {
        $amount = readline("Please enter how many players you would like to play with? (2 - 12) => .. ");

        if (!is_numeric($amount) || $amount < 2 || $amount > 17) {
            echo "Invalid amount of players, try again" . PHP_EOL;
            die;
        } else {
            switch ($AutoPlay) {
                case 'friends':

                    for ($x = 0; $x < $amount ; $x++) { 
                        while(true) {
                            $name = readline("What's your name? => ..  ");

                            if (!preg_match('/^[a-z]*$/i', $name) || empty($name)) {
                                echo 'Invalid name, please try again. ' . PHP_EOL;
                            } else {
                                $bet = readline($name . ', please place your bet: (5 - 500) => .. ');

                                if (!is_numeric($bet) || $bet < 5 || $bet > 500 || empty($bet)) {
                                    echo 'Invalid bet, please try again.' . PHP_EOL;
                                } else {
                                    break;
                                }
                            }
                        }
                        $dealer->addPlayers(new Player($name, $bet));
                    }
                case 'auto':
                    shuffle($name);

                    for ($x = 0; $x < $amount; $x++) {
                        $dealer->addPlayers(new Player(array_pop($name), random_int(5, 500)));
                    }
                }
            }
        $dealer->addPlayers(new Player('Dealer', 0));
        echo PHP_EOL . "Let's Play!";
        $dealer->dealCards();
        $dealer->playGame();
    }
}
























// private function playGame()
// {
//     foreach ($this->players as $player) {
//         foreach ($player->playingHands as $hand) {
//             $index = (count($player->hands) - 1);
//             $hand = $player->hands[$index];
//             $turn = ($index != 0) ? ": hand $index" : "'s turn";
//             echo $player->name . $turn;

//             for ($y = 0; $y < count($player->hands); $y++) { 
//                 $points = $this->BJ->points($hand);
//             }
//         }
//     }
// } 
