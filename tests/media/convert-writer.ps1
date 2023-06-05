Remove-Item test-alac-writer.* -ErrorAction SilentlyContinue
Remove-Item test-writer.* -ErrorAction SilentlyContinue

Copy-Item -Path test.aac -Destination test-writer.aac -Force
Copy-Item -Path test-alac.m4a -Destination test-alac-writer.m4a -Force
Copy-Item -Path test.aif -Destination test-writer.aif -Force
Copy-Item -Path test.aifc -Destination test-writer.aifc -Force
Copy-Item -Path test.aiff -Destination test-writer.aiff -Force
# Copy-Item -Path test.dsf -Destination test-writer.dsf -Force
Copy-Item -Path test.flac -Destination test-writer.flac -Force
Copy-Item -Path test.mka -Destination test-writer.mka -Force
Copy-Item -Path test.mkv -Destination test-writer.mkv -Force
# Copy-Item -Path test.ape -Destination test-writer.ape -Force
Copy-Item -Path test.mp3 -Destination test-writer.mp3 -Force
Copy-Item -Path test.mp4 -Destination test-writer.mp4 -Force
Copy-Item -Path test.m4a -Destination test-writer.m4a -Force
Copy-Item -Path test.m4b -Destination test-writer.m4b -Force
Copy-Item -Path test.m4v -Destination test-writer.m4v -Force
# Copy-Item -Path test.mpc -Destination test-writer.mpc -Force
Copy-Item -Path test.ogg -Destination test-writer.ogg -Force
Copy-Item -Path test.opus -Destination test-writer.opus -Force
# Copy-Item -Path test.ofr -Destination test-writer.ofr -Force
# Copy-Item -Path test.ofs -Destination test-writer.ofs -Force
Copy-Item -Path test.spx -Destination test-writer.spx -Force
# Copy-Item -Path test.tak -Destination test-writer.tak -Force
Copy-Item -Path test.tta -Destination test-writer.tta -Force
Copy-Item -Path test.wma -Destination test-writer.wma -Force
Copy-Item -Path test.wv -Destination test-writer.wv -Force
Copy-Item -Path test.wav -Destination test-writer.wav -Force
Copy-Item -Path test.webm -Destination test-writer.webm -Force
