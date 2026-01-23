# Convert

```sh
ffmpeg -i in.mp3 -map_metadata 0 -c:a flac out.flac
ffmpeg -i in.mp3 -map_metadata 0 -c:a pcm_s16le out.wav
ffmpeg -i in.mp3 -map_metadata 0 -c:a pcm_s16be out.aiff
ffmpeg -i in.mp3 -map_metadata 0 -c:a pcm_s16be out.aifc
ffmpeg -i in.mp3 -map_metadata 0 -c:a tta out.tta
ffmpeg -i in.mp3 -map_metadata 0 -c:a wavpack out.wv
ffmpeg -i in.mp3 -vn -c:a aac -b:a 128k out.m4b
ffmpeg -i in.mp3 -map_metadata 0 -c:a libopus -b:a 128k out.opus
ffmpeg -i in.mp3 -map_metadata 0 -c:a wmav2 -b:a 192k out.wma
ffmpeg -i in.mp3 -map_metadata 0 -c:a copy out.mka
ffmpeg -i in.mp3 -map_metadata 0 -c:a copy out.mkv
ffmpeg -i in.mp3 -map_metadata 0 -c:a libopus -b:a 128k -vn out.webm
ffmpeg -i in.mp3 -map_metadata 0 -c:a aac -b:a 256k -c:v copy out_aac.m4a
ffmpeg -i in.mp3 -map_metadata 0 -b:a 256k -c:v copy out.m4a
ffmpeg -i in.mp3 -map_metadata 0 -c:a aac -b:a 256k -c:v copy out.mp4
ffmpeg -i in.mp3 -map_metadata 0 -c:a alac -vn out_alac.m4a
ffmpeg -i in.mp3 -vn -map_metadata 0 -c:a libopus -b:a 64k out.ogg
ffmpeg -i in.mp3 -c:a pcm_s16le out.caf
```
