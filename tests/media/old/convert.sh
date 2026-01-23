#/bin/sh

ffmpeg -y -i test.mp3 -acodec pcm_u8 -ar 22050 test.wav
ffmpeg -y -i test.mp3 -c:a aac -vn test.m4a
ffmpeg -y -i test.mp3 test.flac
ffmpeg -y -f lavfi -i color=c=black:s=1280x720:r=5 -i test.mp3 -crf 0 -c:a copy -shortest test.mp4
ffmpeg -y -i test.mp3 -acodec wmav2 -ab 128k test.wma
ffmpeg -y -i test.mp3 test.aac
ffmpeg -y -i test.mp3 -c:a aac -vn test.m4b
ffmpeg -y -i test.mp3 -c:a libvorbis -q:a 4 test.ogg
ffmpeg -y -i test.flac -c:v copy -c:a alac test-alac.m4a
ffmpeg -y -i test.mp3 -f aiff -ab 128000 -ar 44100 test.aif
ffmpeg -y -i test.mp3 -f aiff -ab 128000 -ar 44100 test.aifc
ffmpeg -y -i test.mp3 -f aiff -ab 128000 -ar 44100 test.aiff
ffmpeg -y -i test.mp3 -c:a copy test.mka
ffmpeg -y -i test.mp3 -c:a copy test.mkv
ffmpeg -y -i test.mp3 -c:v copy -c:a aac -strict experimental test.m4v
ffmpeg -y -i test.mp3 -c:a libopus test.opus
ffmpeg -y -i test.mp3 -c:a libspeex test.spx
ffmpeg -y -i test.mp3 -c:a libvorbis -q:a 4 test.webm
ffmpeg -y -i test.mp3 -c:a tta test.tta
ffmpeg -y -i test.mp3 -acodec test.wv

# dsf: https://sourceforge.net/projects/sacddecoder/
# `sacd_extract -i input.mp3 -s`

# ape: http://www.monkeysaudio.com/download.html
# `mac.exe input.mp3 output.ape`

# mpc: http://www.musepack.net/index.php?pg=windows
# `mpcenc -q input.mp3 output.mpc`

# ofr: http://www.losslessaudio.org/
# `ofr -e input.mp3 output.ofr`

# oft: http://www.losslessaudio.org/
# `oft -e input.mp3 output.ofs`

# tak: http://www.thbeck.de/Tak/Tak.html
# `takc.exe input.mp3 output.tak`
