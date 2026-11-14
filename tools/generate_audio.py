#!/usr/bin/env python3
"""重建 audio/ 下的 71 個五十音發音檔（微軟 Edge TTS，日語女聲 Nanami）。

用法：
    pip install edge-tts
    python3 tools/generate_audio.py

產生的 mp3 以羅馬字命名（a.mp3、ka.mp3 …），與站台程式預期一致。
想用自己的錄音取代某個音，把同名 mp3 放進 audio/ 即可（後台音頻頁可逐音上傳，優先於 TTS）。"""
import asyncio, pathlib
import edge_tts

VOICE = "ja-JP-NanamiNeural"
KANA = [
    ("あ","a"),
    ("い","i"),
    ("う","u"),
    ("え","e"),
    ("お","o"),
    ("か","ka"),
    ("き","ki"),
    ("く","ku"),
    ("け","ke"),
    ("こ","ko"),
    ("さ","sa"),
    ("し","si"),
    ("す","su"),
    ("せ","se"),
    ("そ","so"),
    ("た","ta"),
    ("ち","ti"),
    ("つ","tu"),
    ("て","te"),
    ("と","to"),
    ("な","na"),
    ("に","ni"),
    ("ぬ","nu"),
    ("ね","ne"),
    ("の","no"),
    ("は","ha"),
    ("ひ","hi"),
    ("ふ","hu"),
    ("へ","he"),
    ("ほ","ho"),
    ("ま","ma"),
    ("み","mi"),
    ("む","mu"),
    ("め","me"),
    ("も","mo"),
    ("や","ya"),
    ("ゆ","yu"),
    ("よ","yo"),
    ("ら","ra"),
    ("り","ri"),
    ("る","ru"),
    ("れ","re"),
    ("ろ","ro"),
    ("  わ","wa"),
    ("を","wo"),
    ("ん","n"),
    ("が","ga"),
    ("ぎ","gi"),
    ("ぐ","gu"),
    ("げ","ge"),
    ("ご","go"),
    ("ざ","za"),
    ("じ","zi"),
    ("ず","zu"),
    ("ぜ","ze"),
    ("ぞ","zo"),
    ("だ","da"),
    ("ぢ","di"),
    ("づ","du"),
    ("で","de"),
    ("ど","do"),
    ("ば","ba"),
    ("び","bi"),
    ("ぶ","bu"),
    ("べ","be"),
    ("ぼ","bo"),
    ("ぱ","pa"),
    ("ぴ","pi"),
    ("ぷ","pu"),
    ("ぺ","pe"),
    ("ぽ","po")
]

async def main():
    out = pathlib.Path(__file__).resolve().parent.parent / "audio"
    out.mkdir(exist_ok=True)
    for kana, romaji in KANA:
        target = out / f"{romaji}.mp3"
        if target.exists():
            continue
        await edge_tts.Communicate(kana, VOICE).save(str(target))
        print("generated", target.name)

if __name__ == "__main__":
    asyncio.run(main())
