const board = document.getElementById("game-board");
const gridSize = 20;
let snake, food, direction, timer;
let endTimer, endState;
let session_token;

function draw () {
    board.innerHTML = "";

    let isHead = true
    snake.forEach(segment => {
        const snakeSegment = document.createElement("img");
        if (isHead) {
            snakeSegment.src = "views/images/train.png"
            isHead = false
        } else {
            snakeSegment.src = "views/images/freight2.png"
        }
        snakeSegment.classList.add("snake");
        snakeSegment.style.left = `${segment.x * gridSize}px`;
        snakeSegment.style.top = `${segment.y * gridSize}px`;

        switch (segment.dir) {
            case "up":
                snakeSegment.style.transform = "rotate(90deg)";
                break;
            case "down":
                snakeSegment.style.transform = "rotate(-90deg)";
                break;
            case "right":
                snakeSegment.style.transform = "scaleX(-1)";
                break;
        }

        board.appendChild(snakeSegment);
    });

    const foodElement = document.createElement("img");
    foodElement.src = "views/images/freight1.png"
    foodElement.classList.add("food");
    foodElement.style.left = `${food.x * gridSize}px`;
    foodElement.style.top = `${food.y * gridSize}px`;
    board.appendChild(foodElement);
}

function generateFood () {
    do {
        food = {
            x: Math.floor(Math.random() * gridSize),
            y: Math.floor(Math.random() * gridSize)
        };
    } while (snake.some(segment => segment.x === food.x && segment.y === food.y));
}

function move () {
    const head = { ...snake[0] };

    switch (direction) {
        case "up":
            head.y -= 1;
            break;
        case "down":
            head.y += 1;
            break;
        case "left":
            head.x -= 1;
            break;
        case "right":
            head.x += 1;
            break;
    }
    head.dir = direction;

    snake.unshift(head);

    if (head.x === food.x && head.y === food.y) {
        generateFood()
    } else {
        snake.pop();
    }
}

function checkCollision () {
    const head = snake[0];
    return (
        head.x < 0 || head.y < 0 || head.x >= gridSize || head.y >= gridSize ||
        snake.slice(1).some(segment => segment.x === head.x && segment.y === head.y)
    );
}

let highscore;

function flashGameOver () {
    const gameOverText = document.getElementById("game-over");

    endState = !endState
    if (endState) {
        if (highscore) {
            gameOverText.innerHTML = "NEW HIGHSCORE: " + highscore;
        }
        gameOverText.style.display = "block";
    } else {
        gameOverText.style.display = "none";
    }
}

function gameOver () {
    clearInterval(timer);
    document.getElementById("game-over").style.display = "block";
    document.getElementById("reset-box").style.display = "flex";
    endState = true;
    endTimer = setInterval(flashGameOver, 750);
    let form = new FormData();
    form.set("score", snake.length - 1);
    fetch("/index.php?game", {
        method: "POST",
        body: form
    }).then(async (res) => {
        let body = await res.json();

        if (body.is_highscore && endTimer) {
            highscore = body.highscore;
        }
    });
}

function resetGame () {
    clearInterval(endTimer);
    endTimer = undefined;
    highscore = undefined;
    document.getElementById("game-over").innerHTML = "GAME OVER";
    document.getElementById("game-over").style.display = "none";
    document.getElementById("reset-box").style.display = "none";
    snake = [{ x: 5, y: 5 }];
    generateFood();
    direction = "right";
}
resetGame();

function update () {
    move();
    if (checkCollision()) {
        gameOver();
        return;
    }
    draw();
}

function startGame () {
    timer = setInterval(update, 200);
}

document.addEventListener("keydown", event => {
    switch (event.key) {
        case "w":
        case "ArrowUp":
            direction = "up";
            break;
        case "s":
        case "ArrowDown":
            direction = "down";
            break;
        case "a":
        case "ArrowLeft":
            direction = "left";
            break;
        case "d":
        case "ArrowRight":
            direction = "right";
            break;
    }
});

function startAuth () {
    document.getElementById("login-box").style.display = "flex";
    document.getElementById("register-box").style.display = "flex";
    document.getElementById("auth-container").style.display = "flex";
}
function endAuth () {
    clearAuthWarning();
    document.getElementById("login-box").style.display = "none";
    document.getElementById("register-box").style.display = "none";
    document.getElementById("auth-container").style.display = "none";
}

const username = document.getElementById("username");
const password = document.getElementById("password");

let wtimer;

function showAuthWarning () {
    clearAuthWarning();
    document.getElementById("wrong-auth").style.display = "block";
    wtimer = setTimeout(clearAuthWarning, 3000);
}
function clearAuthWarning () {
    clearTimeout(wtimer);
    document.getElementById("wrong-auth").style.display = "none";
}

async function login () {
    let form = new FormData();
    form.set("username", username.value);
    form.set("password", password.value);

    let result = await fetch("/index.php?auth&action=login", {
        method: "POST",
        body: form
    });
    
    if (result.status == 200) {
        endAuth();
        startGame();
    } else {
        showAuthWarning();
    }
}

async function register () {
    let form = new FormData();
    form.set("username", username.value);
    form.set("password", password.value);

    let result = await fetch("/index.php?auth&action=register", {
        method: "POST",
        body: form
    });

    if (result.status == 200) {
        endAuth();
        startGame();
    } else {
        showAuthWarning();
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    if ((await fetch("/index.php?auth&action=validate")).status == 200) {
        startGame();
    } else {
        startAuth();
    }
});