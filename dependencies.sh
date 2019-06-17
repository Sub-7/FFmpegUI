#!/bin/bash

sudo apt update -qq && sudo apt dist-upgrade -qq && sudo apt -y install \
  autoconf \
  automake \
  build-essential \
  cmake \
  git-core \
  libass-dev \
  libfreetype6-dev \
  libsdl2-dev \
  libtool \
  libva-dev \
  vdpau-va-driver \
  libvorbis-dev \
  libxcb1-dev \
  libxcb-shm0-dev \
  libxcb-xfixes0-dev \
  pkg-config \
  texinfo \
  wget \
  zlib1g-dev \
  gcc \
  i965-va-driver \
  vainfo \
  vdpauinfo \
  libx264-dev \
  libx265-dev \
  libnuma-dev \
  libvpx-dev \
  libfdk-aac-dev \
  libmp3lame-dev \
  libopus-dev \
  libvdpau-dev \
  software-properties-common \
  yasm
  
sudo apt autoremove -y
echo '\n'"$(tput setaf 2) Dependencies are installed.$(tput sgr 0)"'\n'

