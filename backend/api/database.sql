-- 创建数据库
CREATE DATABASE IF NOT EXISTS japanese_learning DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE japanese_learning;

-- 闪卡表
CREATE TABLE IF NOT EXISTS flashcards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    japanese VARCHAR(100) NOT NULL,
    hiragana VARCHAR(100) NOT NULL,
    romaji VARCHAR(100) NOT NULL,
    meaning VARCHAR(255),
    -- 諧音字段，非必填
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 练习会话表
CREATE TABLE IF NOT EXISTS practice_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_cards INT NOT NULL,
    total_time FLOAT NOT NULL,
    total_errors INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 练习记录表
CREATE TABLE IF NOT EXISTS practice_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    flashcard_id INT NOT NULL,
    time_taken FLOAT NOT NULL,
    is_correct BOOLEAN NOT NULL,
    showed_answer BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES practice_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (flashcard_id) REFERENCES flashcards(id) ON DELETE CASCADE
);

-- 插入一些示例数据
INSERT INTO flashcards (japanese, hiragana, romaji, meaning) VALUES
('あ', 'ア', 'a', '啊'),
('い', 'イ', 'i', '伊'),
('う', 'ウ', 'u', '宇'),
('え', 'エ', 'e', '衣'),
('お', 'オ', 'o', '於'),
('か', 'カ', 'ka', '加'),
('き', 'キ', 'ki', '机'),
('く', 'ク', 'ku', '久'),
('け', 'ケ', 'ke', '计'),
('こ', 'コ', 'ko', '己');