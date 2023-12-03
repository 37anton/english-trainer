document.getElementById('startGameButton').addEventListener('click', function() {
    startGame();
});

function startGame() {
    console.log("I press the button that starts the game");
    localStorage.setItem('currentScore', 0);

    // Cacher le bouton de démarrage
    document.getElementById('startGameButton').style.display = 'none';

    fetch('/game/start', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(handleGameStartResponse)
    .catch(error => {
        console.error("Erreur AJAX :", error);
    });

}

function handleGameStartResponse(data) {
    if (data.gameId && data.wordsList) {
        localStorage.setItem('wordsList', JSON.stringify(data.wordsList));
        localStorage.setItem('currentWordIndex', 0); // Initialiser l'index du mot actuel
        displayWord(data.wordsList[0]);
    } else {
        console.error("Erreur lors du démarrage de la partie");
    }
}

function displayWord(word) {
    document.getElementById('wordToTranslate').textContent = word.translation;
    localStorage.setItem('currentWordId', word.id);
}

document.getElementById('verifyButton').addEventListener('click', function(event) {
    event.preventDefault(); // Pour empêcher le comportement par défaut du bouton submit
    verifyTranslation();
});

function verifyTranslation() {
    console.log("je vérifie");
    const userTranslation = document.getElementById('traduction_form_translatedWord').value;
    const currentWordId = localStorage.getItem('currentWordId');

    console.log(currentWordId, userTranslation);

    fetch('/verify/translation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ wordId: currentWordId, userTranslation: userTranslation })
    })
    .then(response => response.json())
    .then(handleVerificationResponse)
    .catch(error => console.error("Erreur AJAX :", error));
}

function handleVerificationResponse(data) {
    if (data.correct) {
        console.log("Réponse correcte !");
        updateAndDisplayScore(true);
    } else {
        console.log("Réponse incorrecte.");
        updateAndDisplayScore(false);
    }
    displayNextWord();
}

function incrementScore() {
    let score = localStorage.getItem('currentScore') || 0;
    score++;
    localStorage.setItem('currentScore', score);
}

function displayNextWord() {
    let wordsList = JSON.parse(localStorage.getItem('wordsList'));
    let currentIndex = parseInt(localStorage.getItem('currentWordIndex')) || 0;

    currentIndex++;
    if (currentIndex < wordsList.length) {
        localStorage.setItem('currentWordIndex', currentIndex);
        displayWord(wordsList[currentIndex]);
    } else {
        console.log("Fin de la partie !");
        endGame();
    }
}

function updateAndDisplayScore(isCorrect) {
    let score = parseInt(localStorage.getItem('currentScore')) || 0;
    
    if (isCorrect) {
        score++;
        localStorage.setItem('currentScore', score);
    }

    console.log("Score actuel:", score);
}

function endGame() {
    let finalScore = localStorage.getItem('currentScore');
    let gameId = localStorage.getItem('currentGameId');

    fetch('/game/update-score', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ gameId: gameId, finalScore: finalScore })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        // Ici, vous pouvez gérer la fin de la partie, afficher les résultats, etc.
    })
    .catch(error => console.error("Erreur lors de la mise à jour du score :", error));
}
