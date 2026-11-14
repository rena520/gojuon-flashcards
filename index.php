<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>五十音閃卡 - 日語五十音練習</title>
    <meta name="description" content="日語五十音閃卡練習：平假名、片假名與濁音的看字練習與聽寫練習，逐題計時、記錄成績，還能上傳自己的錄音替換機器朗讀。">
    <meta name="keywords" content="日語,五十音,平假名,片假名,濁音,半濁音,假名練習,聽寫,日語學習,日本語,ひらがな,カタカナ,ローマ字">
    <meta property="og:title" content="五十音閃卡 - 日語五十音練習">
    <meta property="og:description" content="五十音看字＋聽寫閃卡練習，逐題計時記錄成績，可上傳自錄音頻。">
    <meta property="og:type" content="website">
    <style>
        :root {
            --grad-brand: linear-gradient(92deg, #7c5cff, #e044a8);
            --c-brand: #8b5cf6;
            --c-brand-deep: #7c5cff;
            --c-pink: #e044a8;
            --ink: #2f2a3a;
            --ink-soft: #6f6880;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Hiragino Sans", "Noto Sans JP", "PingFang TC", "Microsoft JhengHei", Arial, sans-serif;
            color: var(--ink);
            line-height: 1.6;
            min-height: 100vh;
            background:
                radial-gradient(1100px 520px at 85% -5%, rgba(255, 190, 220, .55), transparent 60%),
                radial-gradient(900px 480px at -10% 10%, rgba(196, 181, 253, .5), transparent 60%),
                radial-gradient(700px 500px at 50% 110%, rgba(165, 243, 252, .45), transparent 60%),
                linear-gradient(180deg, #f7f3fc, #f3f0fa);
            background-attachment: fixed;
        }
        .container { max-width: 800px; margin: 0 auto; padding: 28px 20px 40px; }
        h1 {
            text-align: center; margin-bottom: 26px; font-size: 30px; letter-spacing: .04em;
            background: var(--grad-brand); -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .start-screen, .practice-screen, .summary-screen {
            background: rgba(255, 255, 255, .86);
            backdrop-filter: blur(10px);
            padding: 30px; border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, .8);
            box-shadow: 0 18px 40px -18px rgba(124, 92, 255, .28);
            margin-bottom: 20px;
        }
        .start-screen h2, .summary-screen h2 { margin-bottom: 18px; font-size: 21px; color: var(--ink); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 700; color: var(--ink); }
        input[type="number"], input[type="text"] {
            width: 100%; padding: 12px 14px; font-size: 16px;
            border: 1.5px solid #e5def5; border-radius: 12px; background: #fff; color: var(--ink);
            outline: none; transition: border-color .2s, box-shadow .2s;
        }
        input[type="number"]:focus, input[type="text"]:focus {
            border-color: var(--c-brand); box-shadow: 0 0 0 4px rgba(139, 92, 246, .15);
        }
        .mode-picker { display: flex; gap: 12px; }
        .mode-card {
            flex: 1; cursor: pointer; border: 2px solid #e5def5; border-radius: 16px;
            padding: 14px 16px; background: #fff; transition: all .2s;
        }
        .mode-card .mode-title { font-weight: 800; font-size: 16px; margin-bottom: 2px; }
        .mode-card .mode-desc { font-size: 13px; color: var(--ink-soft); line-height: 1.5; }
        .mode-card.selected { border-color: transparent; background: linear-gradient(#fff, #fff) padding-box, var(--grad-brand) border-box; box-shadow: 0 10px 24px -12px rgba(224, 68, 168, .45); }
        button {
            padding: 12px 24px; font-size: 16px; border: none; border-radius: 999px;
            cursor: pointer; margin-right: 10px; margin-bottom: 10px; font-weight: 700;
            transition: transform .15s, box-shadow .15s, opacity .15s;
        }
        button:hover { transform: translateY(-1px); }
        button:active { transform: translateY(0); }
        .btn-primary { background: var(--grad-brand); color: #fff; box-shadow: 0 10px 22px -10px rgba(224, 68, 168, .55); }
        .btn-secondary { background: #fff; color: var(--ink-soft); border: 1.5px solid #e5def5; box-shadow: none; }
        .btn-danger { background: #fff; color: #e11d63; border: 1.5px solid #fbcfe8; box-shadow: none; }
        .btn-success { background: linear-gradient(92deg, #059669, #34d399); color: #fff; box-shadow: 0 10px 22px -10px rgba(5, 150, 105, .5); }
        .timer { text-align: center; margin-bottom: 8px; font-size: 22px; font-weight: 800; color: var(--c-brand-deep); }
        .remaining { text-align: center; margin-bottom: 18px; font-size: 15px; color: var(--ink-soft); }
        .flashcard {
            background: linear-gradient(180deg, rgba(255,255,255,.95), rgba(250,245,255,.9));
            padding: 38px 30px; border-radius: 18px; text-align: center; margin-bottom: 24px;
            border: 1px solid #efe7fb;
            box-shadow: inset 0 1px 0 #fff, 0 12px 28px -16px rgba(124, 92, 255, .25);
        }
        .flashcard h3 { font-size: 56px; margin-bottom: 6px; color: var(--c-brand-deep); line-height: 1.2; }
        .listen-area { display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 8px 0 2px; }
        .play-btn {
            width: 104px; height: 104px; border-radius: 50%; margin: 0;
            background: var(--grad-brand); color: #fff; font-size: 44px; line-height: 1;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 16px 32px -12px rgba(224, 68, 168, .6);
        }
        .play-btn.playing { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.07); } }
        .listen-hint { color: var(--ink-soft); font-size: 15px; }
        .replay-count { color: var(--ink-soft); font-size: 13px; }
        .answer-row { display: flex; gap: 10px; align-items: stretch; }
        .answer-row input { flex: 1; }
        .answer-row button { margin: 0; white-space: nowrap; }
        .feedback { min-height: 28px; text-align: center; font-size: 17px; font-weight: 800; margin: 14px 0 6px; }
        .feedback.correct { color: #059669; }
        .feedback.incorrect { color: #e11d63; }
        .btn-row { text-align: center; margin-top: 10px; }
        .answer-shown {
            text-align: center; background: #faf5ff; border: 1.5px dashed #d8b4fe;
            border-radius: 12px; padding: 12px; margin-top: 12px; font-size: 18px;
        }
        .answer-shown .ans-kana { color: var(--c-brand-deep); font-weight: 800; }
        .hidden { display: none !important; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; margin-bottom: 20px; }
        .stat-box { background: #faf5ff; border-radius: 14px; padding: 12px; text-align: center; }
        .stat-box .num { font-size: 22px; font-weight: 800; color: var(--c-brand-deep); }
        .stat-box .lbl { font-size: 13px; color: var(--ink-soft); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 15px; }
        th, td { padding: 9px 8px; text-align: center; border-bottom: 1px solid #efe7fb; }
        th { color: var(--ink-soft); font-size: 13px; }
        tr.highlight td { background: #fdf2f8; font-weight: 700; }
        .mode-note { color: var(--ink-soft); font-size: 14px; margin-bottom: 14px; }
        .site-footer { text-align: center; color: var(--ink-soft); font-size: 13px; padding: 6px 0 10px; line-height: 1.9; }
        .site-footer a { color: var(--c-brand-deep); text-decoration: none; font-weight: 700; }
        .site-footer a:hover { text-decoration: underline; }
        @media (max-width: 560px) {
            .mode-picker { flex-direction: column; }
            .flashcard h3 { font-size: 44px; }
            .start-screen, .practice-screen, .summary-screen { padding: 22px 18px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>五十音閃卡</h1>

        <!-- 開始畫面 -->
        <div class="start-screen" id="startScreen">
            <h2>開始練習</h2>
            <div class="form-group">
                <label>練習模式</label>
                <div class="mode-picker">
                    <div class="mode-card selected" id="modeVisual">
                        <div class="mode-title">👀 看字練習</div>
                        <div class="mode-desc">看假名，打出另一個假名或羅馬字（原模式）</div>
                    </div>
                    <div class="mode-card" id="modeListening">
                        <div class="mode-title">👂 聽寫練習</div>
                        <div class="mode-desc">不顯示字，只播聲音，打出你聽到的羅馬字</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="cardCount">練習題數（1–71）</label>
                <input type="number" id="cardCount" value="10" min="1" max="71">
            </div>
            <button class="btn-primary" id="startBtn">開始練習</button>
            <p class="mode-note" id="modeNote">聽寫模式會自動排除同音混淆的三張：ぢ（di）、づ（du）、を（wo，唸起來和お同音）。</p>
        </div>

        <!-- 練習畫面 -->
        <div class="practice-screen hidden" id="practiceScreen">
            <div class="timer" id="timer">總時間：0.00 秒</div>
            <div class="remaining" id="remaining"></div>
            <div class="flashcard" id="flashcard">
                <h3 id="japanese"></h3>
                <div class="listen-area hidden" id="listenArea">
                    <button class="play-btn" id="playBtn" title="播放">🔊</button>
                    <div class="listen-hint">仔細聽，打出你聽到的音</div>
                    <div class="replay-count" id="replayCount"></div>
                </div>
            </div>
            <div class="answer-row">
                <input type="text" id="userInput" autocomplete="off" autocapitalize="none" spellcheck="false" placeholder="輸入答案">
                <button class="btn-primary" id="checkBtn">提交</button>
            </div>
            <div class="feedback" id="feedback"></div>
            <div class="btn-row">
                <button class="btn-secondary" id="showAnswerBtn">顯示答案</button>
                <button class="btn-danger" id="skipBtn">跳過</button>
            </div>
            <div class="answer-shown hidden" id="answerShown">答案：<span class="ans-kana" id="answerText"></span></div>
        </div>

        <!-- 小結畫面 -->
        <div class="summary-screen hidden" id="summaryScreen">
            <h2>練習小結</h2>
            <p class="mode-note" id="summaryMode"></p>
            <div class="stats">
                <div class="stat-box"><div class="num" id="totalTime">0</div><div class="lbl">總時間（秒）</div></div>
                <div class="stat-box"><div class="num" id="totalCards">0</div><div class="lbl">總題數</div></div>
                <div class="stat-box"><div class="num" id="correctCount">0</div><div class="lbl">答對</div></div>
                <div class="stat-box"><div class="num" id="errorCount">0</div><div class="lbl">錯誤</div></div>
                <div class="stat-box"><div class="num" id="avgTime">0</div><div class="lbl">平均每題（秒）</div></div>
                <div class="stat-box"><div class="num" id="maxTime">0</div><div class="lbl">最慢一題（秒）</div></div>
            </div>
            <table id="summaryTable">
                <thead>
                    <tr>
                        <th>平假名</th>
                        <th>片假名</th>
                        <th>羅馬字</th>
                        <th>耗時 (秒)</th>
                        <th>是否正確</th>
                        <th>是否顯示答案</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button class="btn-primary" id="restartBtn">重新練習</button>
            <button class="btn-secondary" id="backToStartBtn">返回開始</button>
        </div>

        <footer class="site-footer">
            <div>© 2026 nihon.party ・ 五十音閃卡 — 本站內容僅供學習交流</div>
            <div class="footer-links"><a href="backend/admin/index.php">管理入口</a></div>
        </footer>
    </div>

    <script>
        // 全局變量
        let flashcards = [];
        let currentCardIndex = 0;
        let cardCount = 10;
        let startTime = 0;
        let totalTime = 0;
        let cardStartTime = 0;
        let cardTime = 0;
        let records = [];
        let totalErrors = 0;
        let timerInterval = null;
        let showedAnswer = false;
        let currentShowHiragana = false;
        let practiceMode = 'visual'; // visual=看字, listening=聽寫
        let playCount = 0;
        let advancing = false;
        const LISTEN_EXCLUDE = new Set(['di', 'du', 'wo']); // 同音混淆：ぢ/づ/を
        const audioPlayer = new Audio();

        // DOM元素
        const startScreen = document.getElementById('startScreen');
        const practiceScreen = document.getElementById('practiceScreen');
        const summaryScreen = document.getElementById('summaryScreen');
        const startBtn = document.getElementById('startBtn');
        const cardCountInput = document.getElementById('cardCount');
        const timerEl = document.getElementById('timer');
        const remainingEl = document.getElementById('remaining');
        const flashcardEl = document.getElementById('flashcard');
        const japaneseEl = document.getElementById('japanese');
        const listenArea = document.getElementById('listenArea');
        const playBtn = document.getElementById('playBtn');
        const replayCountEl = document.getElementById('replayCount');
        const userInput = document.getElementById('userInput');
        const feedbackEl = document.getElementById('feedback');
        const checkBtn = document.getElementById('checkBtn');
        const showAnswerBtn = document.getElementById('showAnswerBtn');
        const skipBtn = document.getElementById('skipBtn');
        const answerShownEl = document.getElementById('answerShown');
        const answerTextEl = document.getElementById('answerText');
        const summaryTable = document.getElementById('summaryTable').querySelector('tbody');
        const totalTimeEl = document.getElementById('totalTime');
        const totalCardsEl = document.getElementById('totalCards');
        const correctCountEl = document.getElementById('correctCount');
        const errorCountEl = document.getElementById('errorCount');
        const avgTimeEl = document.getElementById('avgTime');
        const maxTimeEl = document.getElementById('maxTime');
        const summaryModeEl = document.getElementById('summaryMode');
        const restartBtn = document.getElementById('restartBtn');
        const backToStartBtn = document.getElementById('backToStartBtn');
        const modeVisualCard = document.getElementById('modeVisual');
        const modeListeningCard = document.getElementById('modeListening');
        const modeNote = document.getElementById('modeNote');

        // 模式切換
        function setMode(mode) {
            practiceMode = mode;
            modeVisualCard.classList.toggle('selected', mode === 'visual');
            modeListeningCard.classList.toggle('selected', mode === 'listening');
            modeNote.classList.toggle('hidden', mode !== 'listening');
        }
        modeVisualCard.addEventListener('click', () => setMode('visual'));
        modeListeningCard.addEventListener('click', () => setMode('listening'));
        setMode('visual');

        // 播放聲音：有自錄音頻優先，否則用瀏覽器日語朗讀
        function speakTTS(text) {
            try {
                speechSynthesis.cancel();
                const u = new SpeechSynthesisUtterance(text);
                u.lang = 'ja-JP';
                u.rate = 0.85;
                const jaVoice = speechSynthesis.getVoices().find(v => v.lang && v.lang.toLowerCase().startsWith('ja'));
                if (jaVoice) u.voice = jaVoice;
                u.onstart = () => playBtn.classList.add('playing');
                u.onend = () => playBtn.classList.remove('playing');
                u.onerror = () => playBtn.classList.remove('playing');
                speechSynthesis.speak(u);
            } catch (e) { playBtn.classList.remove('playing'); }
        }
        function playSound(card) {
            playCount++;
            replayCountEl.textContent = playCount > 1 ? `已重播 ${playCount - 1} 次` : '';
            if (card.audio) {
                audioPlayer.src = 'audio/' + card.audio;
                audioPlayer.onplay = () => playBtn.classList.add('playing');
                audioPlayer.onended = () => playBtn.classList.remove('playing');
                audioPlayer.play().catch(() => speakTTS((card.japanese || '').trim()));
            } else {
                speakTTS((card.japanese || '').trim());
            }
        }
        playBtn.addEventListener('click', () => {
            const card = flashcards[currentCardIndex];
            if (card) playSound(card);
        });

        // 開始練習
        startBtn.addEventListener('click', async () => {
            cardCount = parseInt(cardCountInput.value);
            // 聽寫模式先用使用者手勢解鎖語音（手機瀏覽器要求）
            if (practiceMode === 'listening') {
                try { const u = new SpeechSynthesisUtterance('スタート'); u.lang = 'ja-JP'; u.volume = 0.6; speechSynthesis.speak(u); } catch (e) {}
                // 用靜音播放一次真實音檔解鎖 audio 元素，之後程式自動播放就不會被手機攔
                try {
                    audioPlayer.volume = 0;
                    audioPlayer.src = 'audio/a.mp3';
                    audioPlayer.play().then(() => { audioPlayer.pause(); audioPlayer.volume = 1; }).catch(() => { audioPlayer.volume = 1; });
                } catch (e) {}
            }
            try {
                const response = await fetch('backend/api/get_flashcards.php');
                const data = await response.json();
                if (data.success) {
                    let pool = data.flashcards.filter(card => card.is_active == 1 || card.is_active == true || card.is_active === '1' || card.is_active === 'true');
                    if (practiceMode === 'listening') {
                        pool = pool.filter(card => !LISTEN_EXCLUDE.has((card.romaji || '').trim().toLowerCase()));
                    }
                    flashcards = pool.sort(() => 0.5 - Math.random()).slice(0, cardCount);
                    currentCardIndex = 0;
                    records = [];
                    totalErrors = 0;
                    totalTime = 0;
                    advancing = false;
                    startScreen.classList.add('hidden');
                    practiceScreen.classList.remove('hidden');
                    summaryScreen.classList.add('hidden');
                    startTimer();
                    loadCard();
                } else {
                    alert('獲取閃卡失敗: ' + data.message);
                }
            } catch (error) {
                alert('獲取閃卡失敗: ' + error.message);
            }
        });

        function startTimer() {
            startTime = Date.now();
            timerInterval = setInterval(() => {
                totalTime = (Date.now() - startTime) / 1000;
                timerEl.textContent = `總時間：${totalTime.toFixed(2)} 秒`;
            }, 100);
        }
        function stopTimer() { clearInterval(timerInterval); }

        // 加载闪卡
        function loadCard() {
            if (currentCardIndex < flashcards.length) {
                const card = flashcards[currentCardIndex];
                userInput.value = '';
                feedbackEl.textContent = '';
                feedbackEl.className = 'feedback';
                answerShownEl.classList.add('hidden');
                showedAnswer = false;
                playCount = 0;
                replayCountEl.textContent = '';
                if (practiceMode === 'listening') {
                    japaneseEl.textContent = '';
                    japaneseEl.classList.add('hidden');
                    listenArea.classList.remove('hidden');
                    userInput.placeholder = '輸入羅馬字（例如 ka）';
                    setTimeout(() => playSound(card), 300);
                } else {
                    listenArea.classList.add('hidden');
                    japaneseEl.classList.remove('hidden');
                    currentShowHiragana = Math.random() > 0.5;
                    japaneseEl.textContent = currentShowHiragana ? (card.japanese || '').trim() : (card.hiragana || '').trim();
                    userInput.placeholder = '輸入答案';
                }
                remainingEl.textContent = `剩餘題目：${flashcards.length - currentCardIndex} / ${flashcards.length}`;
                cardStartTime = Date.now();
                userInput.focus();
            } else {
                finishPractice();
            }
        }

        // 检查答案
        checkBtn.addEventListener('click', () => {
            if (advancing) return;
            const userAnswer = userInput.value.trim();
            const userLower = userAnswer.toLowerCase();
            const currentCard = flashcards[currentCardIndex];
            cardTime = (Date.now() - cardStartTime) / 1000;
            const hira = (currentCard.japanese || '').trim(); // 欄位 japanese 實際存平假名
            const kata = (currentCard.hiragana || '').trim(); // 欄位 hiragana 實際存片假名
            const romaji = (currentCard.romaji || '').trim().toLowerCase();
            let isCorrect;
            if (practiceMode === 'listening') {
                isCorrect = userLower === romaji || userAnswer === hira || userAnswer === kata;
            } else {
                isCorrect = currentShowHiragana
                    ? userAnswer === kata || userLower === romaji
                    : userAnswer === hira || userLower === romaji;
            }
            if (!isCorrect) totalErrors++;
            if (practiceMode === 'listening') {
                // 揭曉假名，讓眼睛跟耳朵對上
                japaneseEl.textContent = hira;
                japaneseEl.classList.remove('hidden');
                feedbackEl.textContent = isCorrect ? `正確！${hira}（${romaji}）` : `錯誤！正解是 ${hira}（${romaji}）`;
            } else {
                feedbackEl.textContent = isCorrect ? '正確！' : '錯誤！';
            }
            feedbackEl.className = 'feedback ' + (isCorrect ? 'correct' : 'incorrect');
            records.push({
                flashcard_id: currentCard.id,
                time_taken: cardTime,
                is_correct: isCorrect,
                showed_answer: showedAnswer
            });
            advancing = true;
            setTimeout(() => {
                advancing = false;
                currentCardIndex++;
                loadCard();
            }, practiceMode === 'listening' ? 1500 : 1000);
        });

        // 显示答案
        showAnswerBtn.addEventListener('click', () => {
            const currentCard = flashcards[currentCardIndex];
            const hira = (currentCard.japanese || '').trim(); // 欄位 japanese 實際存平假名
            const kata = (currentCard.hiragana || '').trim(); // 欄位 hiragana 實際存片假名
            if (practiceMode === 'listening') {
                answerTextEl.textContent = `${hira} / ${kata}（${(currentCard.romaji || '').trim()}）`;
            } else {
                const answerChar = currentShowHiragana ? kata : hira;
                answerTextEl.textContent = `${answerChar} (${(currentCard.romaji || '').trim()})`;
            }
            answerShownEl.classList.remove('hidden');
            showedAnswer = true;
        });

        // 跳過
        skipBtn.addEventListener('click', () => {
            if (advancing) return;
            cardTime = (Date.now() - cardStartTime) / 1000;
            records.push({
                flashcard_id: flashcards[currentCardIndex].id,
                time_taken: cardTime,
                is_correct: false,
                showed_answer: showedAnswer
            });
            totalErrors++;
            currentCardIndex++;
            loadCard();
        });

        // 完成练习
        async function finishPractice() {
            stopTimer();
            try { speechSynthesis.cancel(); } catch (e) {}
            audioPlayer.pause();
            let saveSuccess = true;
            try {
                const response = await fetch('backend/api/save_practice.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        total_cards: flashcards.length,
                        total_time: totalTime,
                        total_errors: totalErrors,
                        mode: practiceMode,
                        records: records
                    })
                });
                const data = await response.json();
                if (!data.success) {
                    console.error('保存练习记录失败: ' + data.message);
                    alert('保存练习记录失败: ' + data.message);
                    saveSuccess = false;
                }
            } catch (error) {
                console.error('保存练习记录失败: ' + error.message);
                alert('保存练习记录失败: ' + error.message);
                saveSuccess = false;
            }
            practiceScreen.classList.add('hidden');
            summaryScreen.classList.remove('hidden');
            summaryModeEl.textContent = practiceMode === 'listening' ? '本輪模式：👂 聽寫練習' : '本輪模式：👀 看字練習';
            const correctCount = records.filter(r => r.is_correct).length;
            const avgTime = records.length ? totalTime / records.length : 0;
            const maxTime = records.length ? Math.max(...records.map(r => r.time_taken)) : 0;
            totalTimeEl.textContent = totalTime.toFixed(2);
            totalCardsEl.textContent = flashcards.length;
            correctCountEl.textContent = correctCount;
            errorCountEl.textContent = totalErrors;
            avgTimeEl.textContent = avgTime.toFixed(2);
            maxTimeEl.textContent = maxTime.toFixed(2);
            summaryTable.innerHTML = '';
            records.forEach((record, index) => {
                const card = flashcards[index];
                const row = document.createElement('tr');
                if (record.time_taken === maxTime) row.classList.add('highlight');
                row.innerHTML = `
                    <td>${(card.japanese || '').trim()}</td>
                    <td>${(card.hiragana || '').trim()}</td>
                    <td>${(card.romaji || '').trim()}</td>
                    <td>${record.time_taken.toFixed(2)}</td>
                    <td>${record.is_correct ? '✓' : '✗'}</td>
                    <td>${record.showed_answer ? '✓' : '✗'}</td>
                `;
                summaryTable.appendChild(row);
            });
        }

        restartBtn.addEventListener('click', () => { startBtn.click(); });
        backToStartBtn.addEventListener('click', () => {
            summaryScreen.classList.add('hidden');
            startScreen.classList.remove('hidden');
        });
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') checkBtn.click();
        });
        if ('speechSynthesis' in window) { speechSynthesis.getVoices(); speechSynthesis.onvoiceschanged = () => speechSynthesis.getVoices(); }
    </script>
</body>
</html>
