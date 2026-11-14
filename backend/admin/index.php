<?php require_once __DIR__ . '/auth.php'; require_page_auth(); ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>五十音閃卡 - 管理</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4a6fa5;
        }
        
        .card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        h2 {
            margin-bottom: 20px;
            color: #4a6fa5;
            font-size: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .btn-primary {
            background-color: #4a6fa5;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>五十音閃卡 - 閃卡管理</h1>
        <div style="text-align:center; margin:-14px 0 20px;">
            <a href="audio.php" style="color:#7c5cff; font-weight:700; text-decoration:none;">🎙️ 音頻管理</a>
            <span style="color:#b8b0cc;"> ・ </span>
            <a href="../../index.php" style="color:#7c5cff; font-weight:700; text-decoration:none;">回練習前台</a>
            <span style="color:#b8b0cc;"> ・ </span>
            <a href="?logout=1" style="color:#e11d63; font-weight:700; text-decoration:none;">登出</a>
        </div>
        
        <!-- 消息提示 -->
        <div id="message" class="message hidden"></div>
        
        <!-- 添加闪卡 -->
        <div class="card">
            <h2>添加閃卡</h2>
            <form id="addForm">
                <div class="form-group">
                    <label for="japanese">平假名</label>
                    <input type="text" id="japanese" name="japanese" required>
                </div>
                <div class="form-group">
                    <label for="hiragana">片假名</label>
                    <input type="text" id="hiragana" name="hiragana" required>
                </div>
                <div class="form-group">
                    <label for="romaji">羅馬字</label>
                    <input type="text" id="romaji" name="romaji" required>
                </div>
                <div class="form-group">
                    <label for="meaning">諧音</label>
                    <input type="text" id="meaning" name="meaning">
                </div>
                <button type="submit" class="btn-primary">添加</button>
            </form>
        </div>
        
        <!-- 闪卡列表 -->
        <div class="card">
            <h2>閃卡列表</h2>
            <div class="table-container">
                <table id="flashcardTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>平假名</th>
                            <th>片假名</th>
                            <th>羅馬字</th>
                            <th>激活状态</th>
                            <th>平均计时</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="flashcardList">
                        <!-- 闪卡列表将通过JavaScript动态生成 -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- 编辑闪卡 -->
        <div class="card hidden" id="editCard">
            <h2>编辑閃卡</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <div class="form-group">
                    <label for="editJapanese">平假名</label>
                    <input type="text" id="editJapanese" name="japanese" required>
                </div>
                <div class="form-group">
                    <label for="editHiragana">片假名</label>
                    <input type="text" id="editHiragana" name="hiragana" required>
                </div>
                <div class="form-group">
                    <label for="editRomaji">羅馬字</label>
                    <input type="text" id="editRomaji" name="romaji" required>
                </div>
                <div class="form-group">
                    <label for="editMeaning">諧音</label>
                    <input type="text" id="editMeaning" name="meaning">
                </div>
                <button type="submit" class="btn-success">保存</button>
                <button type="button" class="btn-secondary" id="cancelEdit">取消</button>
            </form>
        </div>
    </div>
    
    <script>
        // 全局变量
        let flashcards = [];
        
        // DOM元素
        const messageEl = document.getElementById('message');
        const addForm = document.getElementById('addForm');
        const flashcardList = document.getElementById('flashcardList');
        const editCard = document.getElementById('editCard');
        const editForm = document.getElementById('editForm');
        const cancelEdit = document.getElementById('cancelEdit');
        
        // 显示消息
        function showMessage(text, type) {
            messageEl.textContent = text;
            messageEl.className = `message ${type}`;
            messageEl.classList.remove('hidden');
            
            // 3秒后隐藏消息
            setTimeout(() => {
                messageEl.classList.add('hidden');
            }, 3000);
        }
        
        // 获取闪卡列表
        async function getFlashcards() {
            try {
                const response = await fetch('../api/get_flashcards.php');
                const data = await response.json();
                
                if (data.success) {
                    flashcards = data.flashcards;
                    renderFlashcards();
                } else {
                    showMessage('获取闪卡失败: ' + data.message, 'error');
                }
            } catch (error) {
                showMessage('获取闪卡失败: ' + error.message, 'error');
            }
        }
        
        // 渲染闪卡列表
        function renderFlashcards() {
            flashcardList.innerHTML = '';
            
            flashcards.forEach(card => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${card.id}</td>
                    <td>${card.japanese}</td>
                    <td>${card.hiragana}</td>
                    <td>${card.romaji}</td>
                    <td>
                        <button class="btn-${card.is_active ? 'success' : 'secondary'}" onclick="toggleActive(${card.id}, ${!card.is_active})">
                            ${card.is_active ? '停用' : '激活'}
                        </button>
                    </td>
                    <td>${card.avg_time ? parseFloat(card.avg_time).toFixed(2) + 's' : '无数据'}</td>
                    <td>
                        <button class="btn-secondary" onclick="editFlashcard(${card.id})">编辑</button>
                        <button class="btn-danger" onclick="deleteFlashcard(${card.id})">删除</button>
                    </td>
                `;
                flashcardList.appendChild(row);
            });
        }
        
        // 添加闪卡
        addForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(addForm);
            const jsonData = {
                action: 'add',
                japanese: formData.get('japanese'),
                hiragana: formData.get('hiragana'),
                romaji: formData.get('romaji'),
                meaning: formData.get('meaning')
            };
            
            try {
                const response = await fetch('../api/manage_flashcards.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                });
                
                const data = await response.json();
                if (data.success) {
                    showMessage('添加成功', 'success');
                    addForm.reset();
                    getFlashcards();
                } else {
                    showMessage('添加失败: ' + data.message, 'error');
                }
            } catch (error) {
                showMessage('添加失败: ' + error.message, 'error');
            }
        });
        
        // 编辑闪卡
        window.editFlashcard = function(id) {
            const card = flashcards.find(c => c.id == id);
            if (card) {
                document.getElementById('editId').value = card.id;
                document.getElementById('editJapanese').value = card.japanese;
                document.getElementById('editHiragana').value = card.hiragana;
                document.getElementById('editRomaji').value = card.romaji;
                document.getElementById('editMeaning').value = card.meaning;
                editCard.classList.remove('hidden');
            }
        };
        
        // 取消编辑
        cancelEdit.addEventListener('click', () => {
            editCard.classList.add('hidden');
        });
        
        // 保存编辑
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(editForm);
            const jsonData = {
                action: 'update',
                id: formData.get('id'),
                japanese: formData.get('japanese'),
                hiragana: formData.get('hiragana'),
                romaji: formData.get('romaji'),
                meaning: formData.get('meaning')
            };
            
            try {
                const response = await fetch('../api/manage_flashcards.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                });
                
                const data = await response.json();
                if (data.success) {
                    showMessage('更新成功', 'success');
                    editCard.classList.add('hidden');
                    getFlashcards();
                } else {
                    showMessage('更新失败: ' + data.message, 'error');
                }
            } catch (error) {
                showMessage('更新失败: ' + error.message, 'error');
            }
        });
        
        // 删除闪卡
        window.deleteFlashcard = async function(id) {
            if (confirm('确定要删除这个闪卡吗？')) {
                try {
                    const jsonData = {
                        action: 'delete',
                        id: id
                    };
                    
                    const response = await fetch('../api/manage_flashcards.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(jsonData)
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        showMessage('删除成功', 'success');
                        getFlashcards();
                    } else {
                        showMessage('删除失败: ' + data.message, 'error');
                    }
                } catch (error) {
                    showMessage('删除失败: ' + error.message, 'error');
                }
            }
        };
        
        // 切换激活状态
        window.toggleActive = async function(id, isActive) {
            try {
                const jsonData = {
                    action: 'toggle_active',
                    id: id,
                    is_active: isActive
                };
                
                const response = await fetch('../api/manage_flashcards.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                });
                
                const data = await response.json();
                if (data.success) {
                    showMessage(isActive ? '激活成功' : '停用成功', 'success');
                    getFlashcards();
                } else {
                    showMessage('操作失败: ' + data.message, 'error');
                }
            } catch (error) {
                showMessage('操作失败: ' + error.message, 'error');
            }
        };
        
        // 初始化
        getFlashcards();
    </script>
</body>
</html>