# 五十音閃卡 Gojuon Flashcards

給日語初學者的五十音練習站：翻卡自評＋聽寫兩種模式，逐卡計時、每次練習都存進資料庫，用歷史紀錄判斷每張卡的熟練度。

線上版：[nihon.party](https://nihon.party)（同 [ja.nihon.party](https://ja.nihon.party)）

## 功能

- **翻卡模式**：看假名想羅馬字，翻卡自評認識／不認識
- **聽寫模式**：先聽發音再作答（同音卡自動剔除，如 ぢ／づ 與 じ／ず 的重複音）
- **逐卡計時＋熟練度**：每張卡的作答與耗時都入庫，熟練度由歷史紀錄推算，不是一次定生死
- **發音**：71 個音的 TTS 發音（微軟 Edge TTS 日語女聲 Nanami），後台可逐音上傳自己的錄音覆蓋
- **後台管理**：密碼登入（bcrypt 雜湊），卡片增刪改、音頻管理
- 配色與姊妹站 [ツキカハゴロモの手帳](https://www.ivday.com/a/) 統一

## 技術

原生 PHP（7.4+）＋ MySQL，無框架、無建置步驟，丟上任何支援 PHP 的虛擬主機就能跑。

## 安裝

1. 把整個目錄放到網站根目錄。
2. 匯入 `backend/api/database.sql` 建庫建表（內附前 10 張卡）；想一次補齊全部 71 張，再匯入 `backend/api/flashcards_full_seed.sql`。
3. 複製 `backend/api/db.sample.php` 為 `backend/api/db.php`，填入你的資料庫連線資訊。（也可以直接開 `install.php` 用網頁精靈完成這步。）
4. 複製 `backend/api/admin_auth.sample.php` 為 `backend/api/admin_auth.php`，首次開啟後台時依引導設定管理密碼。
5. 生成發音檔（倉庫不含 mp3）：

   ```bash
   pip install edge-tts
   python3 tools/generate_audio.py
   ```

## 目錄

```
index.php               前台練習頁
ja.html                 日語版頁面
install.php             網頁安裝精靈
backend/admin/          後台（卡片管理、音頻管理、鑒權）
backend/api/            API 與資料庫設定（db.php、admin_auth.php 請勿提交版本庫）
tools/generate_audio.py TTS 發音重建腳本
audio/                  發音 mp3（自行生成或後台上傳）
```

## 授權

MIT，見 [LICENSE](LICENSE)。歡迎 fork 去改成你自己的單字卡站。
