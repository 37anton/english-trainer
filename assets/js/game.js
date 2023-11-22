document.getElementById('startGameButton').addEventListener('click', function() {
    startGame();
});

function startGame() {
    console.log("I press the button that starts the game");

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
    if (data.gameId) {
        console.log("Partie commencée, ID de la partie :", data.gameId);

        displayWord(data.firstWord);
        localStorage.setItem('currentGameId', data.gameId);
       
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
        incrementScore();
        displayNextWord();
    } else {
        console.log("Réponse incorrecte.");
        displayNextWord();
    }
}

function incrementScore() {
    let score = localStorage.getItem('currentScore') || 0;
    score++;
    localStorage.setItem('currentScore', score);
}

function displayNextWord() {
    // Logique pour charger et afficher le mot suivant
}