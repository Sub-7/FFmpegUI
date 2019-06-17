# FFmpegUI [![Build Status](https://travis-ci.com/Sub-7/FFmpegUI.svg?branch=master)](https://travis-ci.com/Sub-7/FFmpegUI) ![Crates.io](https://img.shields.io/crates/l/rustc-serialize.svg)
FFmpegUI is a graphical web interface for the famous FFmpeg.

[![FFmpegUI](http://img.youtube.com/vi/nWvz52jdOQs/0.jpg)](http://www.youtube.com/watch?v=nWvz52jdOQs "FFmpegUI")

# Features
- almost everything FFmpeg can do with videos
- Hardware encoding with VAAPI and CUDA*
- Blu-Ray/DVD Ripping support
- PROXMOX KVM & LXC support

*it may be possible that CUDA is not working.
is due to FFmpeg or CUDA,
if you have success please tell me versions of FFmpeg, CUDA and Nvidia drivers.

# Installation
 tested and recommended on Ubuntu 18.04
 
 example as root:

```sh
$ apt -y install git
$ git clone https://github.com/Sub-7/FFmpegUI.git
$ cd ~/FFmpegUI
$ chmod +x setup.sh
$ ./setup.sh
```

add a video file to /var/www/html/FFmpeg_UI/media/input

then go to http://localhost/FFmpeg_UI or http://ip/FFmpeg_UI

# Installation example:

intel CPU supports VAAPI

```sh
 [x] 1 Install Dependencies
 [ ] 2 Install NVIDIA driver
 [ ] 3 Install NVIDIA driver (Proxmox LXC)
 [ ] 4 Install CUDA
 [ ] 5 Install FFmpeg (supports VAAPI+CUDA)
 [x] 6 Install FFmpeg (supports VAAPI)
 [x] 7 Install Apache2 and FFmpegUI
 [ ] 8 reboot
```



# License
MIT
