# FFmpegUI v2.0 [![Build Status](https://travis-ci.com/Sub-7/FFmpegUI.svg?branch=master)](https://travis-ci.com/Sub-7/FFmpegUI) ![Crates.io](https://img.shields.io/crates/l/rustc-serialize.svg)
FFmpegUI is a graphical web interface for the famous FFmpeg 

# Features:
- almost everything FFmpeg can do with videos
- Hardware encoding with VAAPI and CUDA*
- Blu-Ray/DVD Ripping
- PROXMOX KVM & LXC Support
- Filemanager
- Streaming (down OK, up comming soon)

*it may be possible that CUDA is not working.
is due to FFmpeg or CUDA,
if you have success please tell me versions of FFmpeg, CUDA and Nvidia drivers.

# Installation:
 tested and recommended on Ubuntu 20.04
 
 (if you want to convert it for a different distribution, an install.log will be created in the FFmpegUI folder during the installation, there you will find all errors and missing packages.)
 
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
# Known issues:
- Filemanager does not start
# To Do:
- redesign
- get MakeMKV to run in LXC
- get CUDA to run in LXC
# License:
MIT

<img align="left" width="1200" src="https://github.com/Sub-7/FFmpegUI/blob/master/FFmpeg_UI/images/FFmpeg_UI2.0.png">

