#!/bin/bash
#
#
#==============================================================================
    
    OS=$(awk '/DISTRIB_ID=/' /etc/*-release | sed 's/DISTRIB_ID=//' | tr '[:upper:]' '[:lower:]')
    ARCH=$(uname -m | sed 's/x86_//;s/i[3-6]86/32/')
    VERSION=$(awk '/DISTRIB_RELEASE=/' /etc/*-release | sed 's/DISTRIB_RELEASE=//' | sed 's/[.]0/./')
   if [ -z "$OS" ]; then
      OS=$(awk '{print $1}' /etc/*-release | tr '[:upper:]' '[:lower:]')
   fi
   if [ -z "$VERSION" ]; then
      VERSION=$(awk '{print $3}' /etc/*-release)
   fi
   
distribution=$OS$ARCH$VERSION
hr=$(printf '%*s\n' "${COLUMNS:-$(tput cols)}" '' | tr ' ' -)
lshw -C processor | grep product > HW-info/CPU
lshw -C system | grep product > HW-info/SYSTEM
lshw -C display | grep product > HW-info/GPU
CPU=HW-info/CPU
MAINBOARD=HW-info/SYSTEM
GPU=HW-info/GPU
NV_V=HW-info/NV_DR_V


#Menu options
options[0]=" 1 Install Dependencies"
options[1]=" 2 Install NVIDIA driver"
options[2]=" 3 Install NVIDIA driver (Proxmox LXC)"
options[3]=" 4 Install CUDA"
options[4]=" 5 Install FFmpeg (supports VAAPI+CUDA)"
options[5]=" 6 Install FFmpeg (supports VAAPI)"
options[6]=" 7 Install Apache2 and FFmpegUI"
options[7]=" 8 reboot"

#Actions to take based on selection
function ACTIONS {
    
    if [[ ${choices[0]} ]]; then
        #Option 1 selected
        echo $'\n'"$(tput setaf 2) ...setting up Dependencies$(tput sgr 0)"$'\n'
		sh dependencies.sh
    fi
    if [[ ${choices[1]} ]]; then
        #Option 2 selected
        echo $'\n'"$(tput setaf 2) ...setting up NVIDIA Driver$(tput sgr 0)"$'\n'
		wget http://de.download.nvidia.com/XFree86/Linux-x86_64/396.24/NVIDIA-Linux-x86_64-396.24.run
		sh ./NVIDIA-Linux-x86_64-396.24.run
		rm -r NVIDIA-Linux-x86_64-396.24.run
		nvidia-smi
		echo $'\n'"$(tput setaf 2) NVIDIA Driver are installed.$(tput sgr 0)"$'\n'
    fi
	    if [[ ${choices[2]} ]]; then
        #Option 2 selected

        echo $'\n'"$(tput setaf 2) ...setting up NVIDIA Driver$(tput sgr 0)"$'\n'
		wget http://de.download.nvidia.com/XFree86/Linux-x86_64/396.24/NVIDIA-Linux-x86_64-396.24.run
		sh ./NVIDIA-Linux-x86_64-396.24.run --no-kernel-module
		rm -r NVIDIA-Linux-x86_64-396.24.run
		nvidia-smi
		echo $'\n'"$(tput setaf 2) NVIDIA Driver are installed.$(tput sgr 0)"$'\n'

    fi	
    if [[ ${choices[3]} ]]; then
        #Option 3 selected
		echo $'\n'"$(tput setaf 2) ...setting up CUDA$(tput sgr 0)"$'\n'
		if [ -e "cuda_9.2.148_396.37_linux" ]; then
		    apt install -y libxmu-dev
		    sh ./cuda_9.2.148_396.37_linux -silent -verbose -toolkit -samples
		  else
		    wget https://developer.nvidia.com/compute/cuda/9.2/Prod2/local_installers/cuda_9.2.148_396.37_linux
            apt install -y libxmu-dev
			sh ./cuda_9.2.148_396.37_linux -silent -verbose -toolkit -samples

		fi
			echo "export LD_LIBRARY_PATH=/usr/local/cuda/lib64" >> ~/.profile
			echo "PATH='/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/usr/local/cuda-9.2/bin'" >> ~/.profile
			echo "/usr/local/cuda-9.2/lib64" >> /etc/ld.so.conf
            echo "/usr/local/cuda-9.2/bin" >> /etc/ld.so.conf
            echo "/usr/local/cuda/include" >> /etc/ld.so.conf
			sudo source ~/.profile
			sudo ldconfig
		
		echo $'\n'"$(tput setaf 2) CUDA are installed.$(tput sgr 0)"$'\n'
    fi
    if [[ ${choices[4]} ]]; then
        #Option 4 selected
		
		echo $'\n'"$(tput setaf 2) ...setting up FFmpeg VAAPI+CUDA$(tput sgr 0)"$'\n'
		
git clone https://git.videolan.org/git/ffmpeg/nv-codec-headers.git && \
cd ~/FFmpegUI_v.1.0/nv-codec-headers && \
make && \
sudo make install
rm -R ~/FFmpegUI_v.1.0/nv-codec-headers



mkdir -p ~/ffmpeg_sources ~/bin

cd ~/ffmpeg_sources && \
wget https://www.nasm.us/pub/nasm/releasebuilds/2.13.03/nasm-2.13.03.tar.bz2 && \
tar xjvf nasm-2.13.03.tar.bz2 && \
cd nasm-2.13.03 && \
./autogen.sh && \
PATH="$HOME/bin:$PATH" ./configure --prefix="$HOME/ffmpeg_build" --bindir="$HOME/bin" && \
make && \
make install

cd ~/ffmpeg_sources && \
git -C aom pull 2> /dev/null || git clone --depth 1 https://aomedia.googlesource.com/aom && \
mkdir aom_build && \
cd aom_build && \
PATH="$HOME/bin:$PATH" cmake -G "Unix Makefiles" -DCMAKE_INSTALL_PREFIX="$HOME/ffmpeg_build" -DENABLE_SHARED=off -DENABLE_NASM=on ../aom && \
PATH="$HOME/bin:$PATH" make && \
make install

cd ~/ffmpeg_sources && \
wget -O ffmpeg-snapshot.tar.bz2 https://ffmpeg.org/releases/ffmpeg-snapshot.tar.bz2 && \
tar xjvf ffmpeg-snapshot.tar.bz2 && \
cd ~/ffmpeg_sources/ffmpeg && \
PATH="$HOME/bin:$PATH" PKG_CONFIG_PATH="$HOME/ffmpeg_build/lib/pkgconfig" ./configure \
  --prefix="$HOME/ffmpeg_build" \
  --pkg-config-flags="--static" \
  --extra-cflags="-I$HOME/ffmpeg_build/include" \
  --extra-ldflags="-L$HOME/ffmpeg_build/lib" \
  --extra-libs="-lpthread -lm" \
  --bindir="$HOME/bin" \
  --extra-cflags=-I/usr/local/cuda/include \
  --extra-ldflags=-L/usr/local/cuda/lib64 \
  --enable-gpl \
  --enable-libaom \
  --enable-libass \
  --enable-libfdk-aac \
  --enable-libfreetype \
  --enable-libmp3lame \
  --enable-libopus \
  --enable-libvorbis \
  --enable-libvpx \
  --enable-libx264 \
  --enable-libx265 \
  --enable-vaapi \
  --enable-cuda \
  --enable-cuvid \
  --enable-nvenc \
  --enable-libnpp \
  --enable-nonfree && \
PATH="$HOME/bin:$PATH" make && \
make install && \
hash -r
cp -R ~/bin /usr/local





        echo $'\n'"$(tput setaf 2) FFmpeg VAAPI+CUDA are installed.$(tput sgr 0)"$'\n'
    fi
	
	    if [[ ${choices[5]} ]]; then
        #Option 4 selected
		
		echo $'\n'"$(tput setaf 2) ...setting up FFmpeg VAAPI$(tput sgr 0)"$'\n'
		
git clone https://git.videolan.org/git/ffmpeg/nv-codec-headers.git && \
cd ~/FFmpegUI_v.1.0/nv-codec-headers && \
make && \
sudo make install
rm -R ~/FFmpegUI_v.1.0/nv-codec-headers



mkdir -p ~/ffmpeg_sources ~/bin

cd ~/ffmpeg_sources && \
wget https://www.nasm.us/pub/nasm/releasebuilds/2.13.03/nasm-2.13.03.tar.bz2 && \
tar xjvf nasm-2.13.03.tar.bz2 && \
cd nasm-2.13.03 && \
./autogen.sh && \
PATH="$HOME/bin:$PATH" ./configure --prefix="$HOME/ffmpeg_build" --bindir="$HOME/bin" && \
make && \
make install

cd ~/ffmpeg_sources && \
git -C aom pull 2> /dev/null || git clone --depth 1 https://aomedia.googlesource.com/aom && \
mkdir aom_build && \
cd aom_build && \
PATH="$HOME/bin:$PATH" cmake -G "Unix Makefiles" -DCMAKE_INSTALL_PREFIX="$HOME/ffmpeg_build" -DENABLE_SHARED=off -DENABLE_NASM=on ../aom && \
PATH="$HOME/bin:$PATH" make && \
make install

cd ~/ffmpeg_sources && \
wget -O ffmpeg-snapshot.tar.bz2 https://ffmpeg.org/releases/ffmpeg-snapshot.tar.bz2 && \
tar xjvf ffmpeg-snapshot.tar.bz2 && \
cd ~/ffmpeg_sources/ffmpeg && \
PATH="$HOME/bin:$PATH" PKG_CONFIG_PATH="$HOME/ffmpeg_build/lib/pkgconfig" ./configure \
  --prefix="$HOME/ffmpeg_build" \
  --pkg-config-flags="--static" \
  --extra-cflags="-I$HOME/ffmpeg_build/include" \
  --extra-ldflags="-L$HOME/ffmpeg_build/lib" \
  --extra-libs="-lpthread -lm" \
  --bindir="$HOME/bin" \
  --enable-gpl \
  --enable-libaom \
  --enable-libass \
  --enable-libfdk-aac \
  --enable-libfreetype \
  --enable-libmp3lame \
  --enable-libopus \
  --enable-libvorbis \
  --enable-libvpx \
  --enable-libx264 \
  --enable-libx265 \
  --enable-vaapi \
  --enable-nonfree && \
PATH="$HOME/bin:$PATH" make && \
make install && \
hash -r
cp -R ~/bin /usr/local





        echo $'\n'"$(tput setaf 2) FFmpeg VAAPI are installed.$(tput sgr 0)"$'\n'
    fi
	
    if [[ ${choices[6]} ]]; then
        #Option 5 selected
		echo $'\n'"$(tput setaf 2) ...setting up FFmpegUI and Apache2$(tput sgr 0)"$'\n'
		apt-add-repository ppa:ondrej/php
		add-apt-repository ppa:eugenesan/ppa
		sudo apt update -qq && sudo apt dist-upgrade -qq && sudo apt -y install \
        php7.0-bcmath php7.0-zip php7.0-dev php7.0-xml php-pear apache2 libapache2-mod-php7.0 rar
        pecl -v install rar
        sed -i '2iextension=rar.so' /etc/php/7.0/apache2/php.ini
        cp -R FFmpeg_UI /var/www/html/
        sudo usermod -a -G video www-data
        sudo usermod -a -G video root
        chown -R www-data /var/www/html/FFmpeg_UI
        echo $'\n'"$(tput setaf 2) FFmpegUI and Apache2 are installed.$(tput sgr 0)"$'\n'
		
        echo Your username for FFmpegUI is: "$(tput setaf 2)admin$(tput sgr 0)"
        htpasswd -c /var/.htpasswd admin
        echo $'\n'"$(tput setaf 3) now edit: /etc/apache2/apache2.conf
         search for <Directory /var/www/>
         repleace AllowOverride None
         by AllowOverride All
         then: service apache2 restart$(tput sgr 0)"$'\n'
		 
		 echo "$(tput setaf 2) We did it, installation is complete$(tput sgr 0)"
		 echo "$(tput setaf 2) visit http://your-host/FFmpeg_UI$(tput sgr 0)"$'\n'
	fi
	if [[ ${choices[7]} ]]; then
        #Option 1 selected
        echo $'\n'"$(tput setaf 2) ...rebooting$(tput sgr 0)"$'\n'
		sudo reboot		
    fi
}

#Variables
ERROR=" "

#Clear screen for menu
clear

#Menu function
function MENU {

if [ $distribution = "ubuntu6416.4" ]; then
      echo $'\n'$hr"$(tput setaf 2) FFmpegUI v.1.0 installation (VAAPI + CUDA)$(tput sgr 0)"$'\n'$hr
      echo  " Distribution: $(tput setaf 2)$OS $ARCH $VERSION   $(tput sgr 0)"
    fi

    
    while read -r line
    do
    name="$line"
	echo " MB  : $(tput setaf 2)${name:9}$(tput sgr 0)"
    done < "$MAINBOARD"
	
	
	while read -r line
    do
    name="$line"
	echo " CPU : $(tput setaf 2)${name:9}$(tput sgr 0)"
    done < "$CPU"
	
	while read -r line
    do
    name="$line"
	echo " GPU : $(tput setaf 2)${name:9}$(tput sgr 0)"
    done < "$GPU"
	
	
	if [ -e "/usr/bin/nvidia-smi" ]; then
      NV_VERSION=$(head -n 1 $NV_V)
	  echo " Nvidia Driver:$(tput setaf 2)${NV_VERSION:9}$(tput sgr 0)"
      else 
      echo  " Nvidia driver:$(tput setaf 1)  is not installed$(tput sgr 0)"
    fi
	
	if [ -e "/usr/local/cuda/version.txt" ]; then
      CUDA_V=$(cat /usr/local/cuda/version.txt)	
      echo  " CUDA Version:$(tput setaf 2) ${CUDA_V:13}$(tput sgr 0)"
      else 
      echo  " CUDA:$(tput setaf 1)           is not installed$(tput sgr 0)"
    fi
	
    if [ -e "/dev/dri/renderD128" ]; then
	  echo  " renderD128: $(tput setaf 2)  OK$(tput sgr 0)"$'\n'
      else
	  echo  " renderD128: $(tput setaf 1)   does not exist.$(tput sgr 0)!!! please refer cpu_passthrough.txt "$'\n'
    fi
		
	echo $hr


    echo $'\n'" Install Options"$'\n'
    for NUM in ${!options[@]}; do
        echo " [""${choices[NUM]:- }""]""${options[NUM]}"
    done
    echo "$ERROR"
}

#Menu loop
while MENU && read -e -p " Select options 1-5 (ENTER when done): " -n1 SELECTION && [[ -n "$SELECTION" ]]; do
    clear
    if [[ "$SELECTION" == *[[:digit:]]* && $SELECTION -ge 1 && $SELECTION -le ${#options[@]} ]]; then
        (( SELECTION-- ))
        if [[ "${choices[SELECTION]}" == "x" ]]; then
            choices[SELECTION]=""
        else
            choices[SELECTION]="x"
        fi
            ERROR=" "
    else
        ERROR="Invalid option: $SELECTION"
    fi
done

ACTIONS