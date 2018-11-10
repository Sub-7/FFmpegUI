# FFmpegUI [![Build Status](https://travis-ci.com/Sub-7/FFmpegUI.svg?branch=master)](https://travis-ci.com/Sub-7/FFmpegUI) ![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg)
FFmpegUI is a graphical web interface for the famous FFmpeg.

# Features
- almost everything FFmpeg can do with videos
- Hardware encoding with VAAPI and CUDA
- folder unpack zip or rar
- rename files
- remove spaces
- move and delete files
- download files

# Installation
 tested with Ubuntu 18.04
 
 example as root:

```sh
$ apt -y install git
$ git clone https://github.com/Sub-7/FFmpegUI.git
$ cd ~/FFmpegUI
$ chmod +x setup.sh
$ ./setup.sh
```

add a video file or rar/zip to /var/www/html/FFmpeg_UI/media/input

then go to http://your-ip/FFmpeg_UI

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
