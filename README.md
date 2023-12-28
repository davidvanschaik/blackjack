# BlackJack => 21!

## introduction
This repository contains a sumilation of a Blackjack game using PHP and Object-Oriented Programming (OOP). The project contains multiple PHP files, each file responsible for specific functionality.

## Project Structure
- **Deck.php**: Generates deck. returns a card to Card.php with the suit and value variables.
- **Card.php**: Contains the validation of the cards and the points.
- **Player.php**: Contains info like name, if still playing, bets and cards.
- **Dealer.php**: Keeps track who is still playing or is busted, also checks if dealer will hit or stand. 
- **Blackjack.php**: Contains all the result and bet calculations. 
- **index.php**: Asks user amount of players. Each player enters their name and bet.

## Features
- **Player Interaction**: Interacts with players. Players can Hit or Stand.
- **Deck**: The cards will be generated within Deck. Every card will be removed from Deck when is dealt. Remember a deck has 52 cards.
- **Dealer**: If dealer score < 18. Draw card else dealer stand. Player > 21 busted.
- **Scoring Calculations**: Calculates who won, the dealer or the players and how much they have won. 
- **Win or Loss**: The results will be shown after all players stand or busted.

## Setup 
To play this Blackjack game locally:

1. **Clone Repository**:
```bash
git clone https://github.com/davidvanschaik/blackjack.git
```

2. **Navigate project Directory**:
```bash
cd path/to/blackjack
```

3. **Run index.php and decide with how many players you are playing**:
**Note**: Make sure you have PHP installed.
```bash
php index.php
How many players would you like to play with ... ?
```
Enter amount of players.
Enter name of each player and enter their bet


4. **Play game**:
Each player will be asked to Hit or Stand if points <= 21. At the end the results will appear. Winners, tied with dealer or losers with the bets.