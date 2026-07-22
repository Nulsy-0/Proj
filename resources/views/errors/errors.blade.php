<x-layout :title="trim($__env->yieldContent('title'))">
    <div class="row">
        <div class="col d-flex justify-content-center">
            <div class="text-center">
                <h1 class="text-muted">Error @yield('code'): @yield('message')</h1>
                <p>Play a game while you are here</p>
                <small class="text-muted">
                    PS: Sorry if you're on mobile. I'm too lazy to make this responsive.
                    <span class="ms-2">(_　_)。゜zｚＺ</span>
                </small>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div id="all" class="col d-flex justify-content-center ms-auto me-auto">
            <div id="game" class=""></div>
        </div>
    </div>
    <div class="row mt-3 w-25 d-flex justify-content-center ms-auto me-auto">
        <div class="w-25 d-flex justify-content-center ms-auto me-auto gap-3">
            <div id="score">
                Score: <p id="pts"></p>
            </div>
            <div id="score">
                Best: <p id="bPts"></p>
            </div>
            <button id="btn">Start</button>
        </div>
    </div>

    <div class="d-flex justify-content-center gap-5 mt-5">
        <a class="btn btn-info" href="{{ route('home') }}"><i class="bi bi-house-door-fill"></i> Go Home</a>
        <button class="btn  btn-info" type="button" onclick="history.back(-2)">
            <i class="bi bi-arrow-left"></i> Go Back
        </button>
    </div>
</x-layout>

<script>
    Object.defineProperty(Array.prototype, 'first', {
        value: function() {
            return this[0]
        },
        writable: true,
        configurable: true,
        enumerable: false
    })
    Object.defineProperty(Array.prototype, 'reset', {
        value: function() {
            this.length = 0

            for (let y = 0; y < yPos; y++) {
                this.push(
                    Array.from({
                        length: xPos
                    }, () => 0)
                )
            }

            return this
        },
        writable: true,
        configurable: true,
        enumerable: false
    })

    const all = document.querySelector('#all')
    const btn = document.querySelector('#btn')
    const pts = document.querySelector('#pts')
    const bPts = document.querySelector('#bPts')

    const debug = false
    const xPos = 31
    const yPos = 20
    const dirsMath = {
        "up": [-1, "y"],
        "down": [1, "y"],
        "left": [-1, "x"],
        "right": [1, "x"],
    }

    let lastTime = 0;
    let gameSpeed = 200;
    let animationId = null;
    let game = document.querySelector('#game')
    let gameStat = true
    let dirMaster = 'up'
    let snake = []
    let keyBoard = false
    let apple = {
        x: null,
        y: null
    }
    let size = 4
    let gameArr = []
    let score = 0
    let cubes
    bPts.innerHTML = 0

    game.setAttribute("style", `width: ${xPos * 21}px`)
    all.setAttribute("style", `width: ${xPos * 30}px`)

    function initGame() {
        gameArr.reset()
        snake = []
        score = 0
        size = 4
        dirMaster = 'up'
        keyBoard = false
        apple.x = null
        apple.y = null
        lastTime = 0
        animationId = null
        gameStat = true
        pts.innerHTML = 0
        btn.innerHTML = "Start"
        gameDisp()
        cubes = document.querySelectorAll('.cube')
        draw(true)
    }

    // Initialize game
    initGame()

    function gameDisp() {
        game.innerHTML = ''
        let html = ''

        for (let y = 0; y < yPos; y++) {
            for (let x = 0; x < xPos; x++) {
                html += `<div class="cube" data-s="0" data-x="${x}" data-y="${y}">` + (debug ?
                    `<h6>${x}-${y}</h6></div>` : `</div>`)
            }
        }

        game.innerHTML = html
    }

    function draw(start = false) {
        if (start) {
            for (let i = 0; i < size; i++) {
                gameArr[8 + i][Math.floor(xPos / 2)] = 1
                snake[snake.length] = {
                    y: 8 + i,
                    x: Math.floor(xPos / 2),
                    dir: dirMaster
                }
            }
        }

        cubes.forEach((cube) => {
            const x = Number(cube.getAttribute('data-x'))
            const y = Number(cube.getAttribute('data-y'))
            cube.dataset.s = gameArr[y][x]
        })
    }

    function createApple() {
        while (apple.x == null || apple.y == null || snake.find(px => px.x === apple.x && px.y === apple.y)) {
            apple.x = Math.floor(Math.random() * xPos)
            apple.y = Math.floor(Math.random() * yPos)
        }
        gameArr[apple.y][apple.x] = 1
    }

    function gameLoop(currentTime = 0) {
        const deltaTime = currentTime - lastTime;

        if (deltaTime >= gameSpeed) {
            lastTime = currentTime - (deltaTime % gameSpeed);

            const moved = movePx();
            if (moved === false) {
                keyBoard = false;

                bPts.innerHTML = score;
                score = 0;
                pts.innerHTML = 0;

                btn.innerHTML = "Restart";
                gameStat = true;

                cancelAnimationFrame(animationId);
                return;
            } else {
                keyBoard = false;
                createApple();
                draw();
                keyBoard = true;
            }
        }

        animationId = requestAnimationFrame(gameLoop);
    }

    function start() {
        lastTime = performance.now();
        animationId = requestAnimationFrame(gameLoop);
    }

    function movePx() {
        const head = snake[0]

        for (let i = snake.length - 1; i > 0; i--) {
            snake[i].x = snake[i - 1].x
            snake[i].y = snake[i - 1].y
            snake[i].dir = snake[i - 1].dir
        }

        if (dirMaster === 'up') head.y--
        if (dirMaster === 'down') head.y++
        if (dirMaster === 'left') head.x--
        if (dirMaster === 'right') head.x++

        head.dir = dirMaster

        const body = snake.slice(1)
        const bodyHit = body.find(px => px.x === head.x && px.y === head.y)
        if (head.x < 0 || head.x >= xPos || head.y < 0 || head.y >= yPos || bodyHit) {
            return false
        }

        if (head.x == apple.x && head.y == apple.y) {
            snake[snake.length] = {
                x: apple.x,
                y: apple.y,
                dir: snake[snake.length - 1].dir
            }
            apple.x = null
            apple.y = null
            score++
            pts.innerHTML = score
        }

        gameArr.reset(xPos, yPos)
        snake.forEach(px => {
            gameArr[px.y][px.x] = 1
        })

        return true
    }

    btn.addEventListener("click", function() {
        if (gameStat == true) {
            if (btn.innerHTML === "Restart") {
                initGame()
            }
            gameStat = false
            keyBoard = true
            start()
        }
    })

    function blockScroll(e) {
        if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
        }
    }

    document.addEventListener('keydown', (e) => {
        if (!keyBoard) return;

        blockScroll(e);

        if (e.key === 'ArrowUp' && dirMaster !== 'down') dirMaster = 'up'
        if (e.key === 'ArrowDown' && dirMaster !== 'up') dirMaster = 'down'
        if (e.key === 'ArrowLeft' && dirMaster !== 'right') dirMaster = 'left'
        if (e.key === 'ArrowRight' && dirMaster !== 'left') dirMaster = 'right'
    }, {
        passive: false
    })
</script>

<style>
    :root {
        --color-primary: rgb(145, 255, 0);
        --color-secondary: rgb(127, 225, 0);
        --color-dark: rgb(58, 101, 2);
        --color-text: rgb(109, 191, 0);
        --bg-dark: rgb(50, 50, 50);
    }

    #btn {
        background-color: var(--color-primary);
        border: 5px var(--color-secondary) solid;
        padding: 10px 20px;
        border-radius: 30%;
        color: var(--color-text);
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: transform 0.1s ease, box-shadow 0.1s ease;
    }

    #btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(145, 255, 0, 0.3);
    }

    #btn:active {
        transform: scale(0.95);
    }

    #score {
        background-color: var(--color-primary);
        border: 5px var(--color-secondary) solid;
        padding: 10px 20px;
        border-radius: 30%;
        color: var(--color-text);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 18px;
        font-weight: bold;
    }

    #pts,
    #bPts {
        padding: 0;
        margin: 0;
    }

    #game {
        background-color: var(--color-secondary);
        border: 2px var(--color-secondary) solid;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        border-radius: 3%;
        padding: 8px;
    }

    .cube {
        width: 20px;
        height: 20px;
        border: 1px var(--color-secondary) solid;
        font-size: 9px;
        display: flex;
        justify-content: center;
        align-items: center;
        line-height: 1;
    }

    .cube[data-s="0"] {
        background-color: var(--color-primary);
    }

    .cube[data-s="1"] {
        background-color: var(--color-dark);
    }
</style>
