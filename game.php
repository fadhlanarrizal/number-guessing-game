<?php

function clearScreen() {
    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
        system('cls');
    } else {
        system('clear');
    }
}

function getDifficultyChances($choice) {
    switch ($choice) {
        case 1:
            return 10;
        case 2:
            return 5;
        case 3:
            return 3;
        default:
            return 5;
    }
}

function getDifficultyName($choice) {
    switch ($choice) {
        case 1:
            return "Easy";
        case 2:
            return "Medium";
        case 3:
            return "Hard";
        default:
            return "Medium";
    }
}

function giveHint($number, $guess) {
    $diff = abs($number - $guess);
    if ($diff <= 5) {
        return "Very close!";
    } elseif ($diff <= 10) {
        return "Close!";
    } else {
        return "Far away.";
    }
}

$highScores = [
    "Easy" => null,
    "Medium" => null,
    "Hard" => null,
];

do {
    clearScreen();
    echo "======================================\n";
    echo "🎮 Welcome to the Number Guessing Game!\n";
    echo "======================================\n";
    echo "I'm thinking of a number between 1 and 100.\n";
    echo "Your goal is to guess the number in limited attempts.\n\n";

    echo "Please select the difficulty level:\n";
    echo "1. Easy (10 chances)\n";
    echo "2. Medium (5 chances)\n";
    echo "3. Hard (3 chances)\n\n";
    $difficulty = (int) readline("Enter your choice (1/2/3): ");
    $chances = getDifficultyChances($difficulty);
    $difficultyName = getDifficultyName($difficulty);

    echo "\nGreat! You have selected the {$difficultyName} difficulty level.\n";
    echo "You have {$chances} chances. Let's start the game!\n";

    $numberToGuess = rand(1, 100);
    $startTime = microtime(true);
    $attempts = 0;
    $win = false;

    while ($chances > 0) {
        $guess = (int) readline("Enter your guess: ");
        $attempts++;
        $chances--;

        if ($guess === $numberToGuess) {
            $win = true;
            break;
        } elseif ($guess < $numberToGuess) {
            echo "Incorrect! The number is greater than {$guess}. Hint: " . giveHint($numberToGuess, $guess) . "\n";
        } else {
            echo "Incorrect! The number is less than {$guess}. Hint: " . giveHint($numberToGuess, $guess) . "\n";
        }

        echo "Remaining chances: {$chances}\n";
    }

    $duration = round(microtime(true) - $startTime, 2);

    if ($win) {
        echo "🎉 Congratulations! You guessed the correct number in {$attempts} attempt(s) and {$duration} seconds.\n";
        if ($highScores[$difficultyName] === null || $attempts < $highScores[$difficultyName]) {
            $highScores[$difficultyName] = $attempts;
            echo "🏆 New high score for {$difficultyName} difficulty!\n";
        }
    } else {
        echo "❌ Sorry! You've used all your chances. The correct number was {$numberToGuess}.\n";
    }

    echo "\nHigh Scores:\n";
    foreach ($highScores as $level => $score) {
        $displayScore = $score === null ? "None yet" : "{$score} attempt(s)";
        echo "- {$level}: {$displayScore}\n";
    }

    $playAgain = strtolower(readline("\nDo you want to play again? (y/n): "));
} while ($playAgain === 'y');

echo "\nThank you for playing! 👋\n";

