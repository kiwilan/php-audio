#/bin/sh

ffmpeg -y -i test.mp3 -acodec pcm_u8 -ar 22050 test.wav
ffmpeg -y -i test.mp3 -c:a aac -vn test.m4a
ffmpeg -y -i test.mp3 test.flac
ffmpeg -y -f lavfi -i color=c=black:s=1280x720:r=5 -i test.mp3 -crf 0 -c:a copy -shortest test.mp4
ffmpeg -y -i test.mp3 -acodec wmav2 -ab 128k test.wma
ffmpeg -y -i test.mp3 test.aac
ffmpeg -y -i test.mp3 -c:a aac -vn test.m4b
ffmpeg -y -i test.mp3 -c:a libvorbis -q:a 4 test.ogg
