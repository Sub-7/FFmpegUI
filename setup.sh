#!/bin/bash
#  FFmpegUI V2.0
#  ffmpeg-4.1.3
#  Sub-7 (04.12.2020)
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
mkdir HW-info
distribution=$OS$ARCH
hr=$(printf '%*s\n' "${COLUMNS:-$(tput cols)}" '' | tr ' ' -)
lshw -C processor | grep product > HW-info/CPU
dmidecode -t 2 | grep product > HW-info/SYSTEM
lshw -C display | grep product > HW-info/GPU
CPU=HW-info/CPU
GPU=HW-info/GPU
MAINBOARD=HW-info/SYSTEM
NV_V=HW-info/NV_DR_V
minsize=1

actualsize1=$(wc -c <"$GPU")
actualsize2=$(wc -c <"$MAINBOARD")
actualsize3=$(wc -c <"$CPU")
if [ $actualsize1 -ge $minsize ]; then
    echo size is over $minsize bytes
else
    lshw -C display | grep Product > HW-info/GPU
fi

if [ $actualsize2 -ge $minsize ]; then
    echo size is over $minsize bytes
else
    dmidecode -t 2 | grep Product > HW-info/SYSTEM
fi

if [ $actualsize3 -ge $minsize ]; then
    echo size is over $minsize bytes
else
    lshw -C processor | grep Product > HW-info/CPU
fi
 sudo mv cloudcmd /etc/init.d/
 sudo update-rc.d cloudcmd defaults   
   


#Menu options
options[0]=" 1 Install Dependencies"
options[1]=" 2 blacklist 'nouveau' driver (reboot required)?"
options[2]=" 3 Install NVIDIA GPU driver (Bare Metal + Proxmox KVM)"
options[3]=" 4 Install NVIDIA GPU driver (Proxmox LXC)"
options[4]=" 5 Install CUDA"
options[5]=" 6 Install FFmpeg (supports VAAPI+CUDA)"
options[6]=" 7 Install FFmpeg (supports VAAPI)"
options[7]=" 8 Install Apache2 and FFmpegUI"
options[8]=" 9 reboot"

LOGFILE=install.log
RETAIN_NUM_LINES=10

function logsetup {
    TMP=$(tail -n $RETAIN_NUM_LINES $LOGFILE 2>/dev/null) && echo "${TMP}" > $LOGFILE
    exec > >(tee -a $LOGFILE)
    exec 2>&1
}

function log {
    echo "[$(date --rfc-3339=seconds)]: $*"
}

rm -r install.log
logsetup

#Actions to take based on selection
function ACTIONS {
    
    if [[ ${choices[0]} ]]; then
        #Option 1 selected
        echo $'\n'"$(tput setaf 2) ...setting up Dependencies$(tput sgr 0)"$'\n'
		sh dependencies.sh
    fi
	
    if [[ ${choices[1]} ]]; then
        #Option 2 selected
        echo $'\n'"$(tput setaf 2) ...add 'nouveau' to the blacklist.$(tput sgr 0)"$'\n'
		echo $'blacklist nouveau\noptions nouveau modeset=0' >/etc/modprobe.d/nvidia-installer-disable-nouveau.conf
        update-initramfs -u
        reboot
    fi
	
    if [[ ${choices[2]} ]]; then
        #Option 3 selected
        echo $'\n'"$(tput setaf 2) ...setting up NVIDIA Driver$(tput sgr 0)"$'\n'
		wget http://download.nvidia.com/XFree86/Linux-x86_64/430.14/NVIDIA-Linux-x86_64-430.14.run
		sh ./NVIDIA-Linux-x86_64-430.14.run
		rm -r NVIDIA-Linux-x86_64-430.14.run
		nvidia-smi
		echo $'\n'"$(tput setaf 2) NVIDIA Driver installed.$(tput sgr 0)"$'\n'
    fi
  
    if [[ ${choices[3]} ]]; then
        #Option 4 selected
        echo $'\n'"$(tput setaf 2) ...setting up NVIDIA Driver$(tput sgr 0)"$'\n'
		wget http://download.nvidia.com/XFree86/Linux-x86_64/430.14/NVIDIA-Linux-x86_64-430.14.run
		sh ./NVIDIA-Linux-x86_64-430.14.run --no-kernel-module
		rm -r NVIDIA-Linux-x86_64-430.14.run
		nvidia-smi
		echo $'\n'"$(tput setaf 2) NVIDIA Driver installed.$(tput sgr 0)"$'\n'
    fi	
    if [[ ${choices[4]} ]]; then
        #Option 5 selected
		echo $'\n'"$(tput setaf 2) ...setting up CUDA$(tput sgr 0)"$'\n'
		echo $'\n'"$(tput setaf 2) Please follow the command-line prompts$(tput sgr 0)"$'\n'
		if [ -e "cuda_10.1.168_418.67_linux.run" ]; then
			echo $'\n'"$(tput setaf 2) Found: cuda_10.1.168_418.67_linux.run$(tput sgr 0)"$'\n'
		    apt-get install -y g++ freeglut3-dev build-essential libx11-dev libxmu-dev libxi-dev libglu1-mesa libglu1-mesa-dev
		    sh ./cuda_10.1.168_418.67_linux.run --silent --toolkit --samples
		  else
		    wget https://developer.nvidia.com/compute/cuda/10.1/Prod/local_installers/cuda_10.1.168_418.67_linux.run
                   apt-get -y --force-yes install libxmu-dev
		    sh ./cuda_10.1.168_418.67_linux.run --silent --toolkit --samples

		fi
			echo "export LD_LIBRARY_PATH=/usr/local/cuda/lib64" >> ~/.profile
			echo "PATH='/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/usr/local/cuda-10.1/bin'" >> ~/.profile
			echo "/usr/local/cuda-10.1/lib64" >> /etc/ld.so.conf
                       echo "/usr/local/cuda-10.1/bin" >> /etc/ld.so.conf
                       echo "/usr/local/cuda/include" >> /etc/ld.so.conf
			source ~/.profile
			sudo ldconfig		
			echo $'\n'"$(tput setaf 2) CUDA installed.$(tput sgr 0)"$'\n'
    fi
	
    if [[ ${choices[5]} ]]; then
        #Option 6 selected
		echo $'\n'"$(tput setaf 2) ...setting up FFmpeg VAAPI+CUDA$(tput sgr 0)"$'\n'
		git clone https://github.com/FFmpeg/nv-codec-headers.git && \
        cd ~/FFmpegUI/nv-codec-headers && \
        make -j4 && \
        make install
		
        rm -R ~/FFmpegUI/nv-codec-headers
        mkdir -p ~/ffmpeg_sources ~/bin
        cd ~/ffmpeg_sources && \
        apt -y --force-yes install nasm yasm
		
        # Install libaom from source.
        mkdir -p ~/ffmpeg_sources/libaom && \
        cd ~/ffmpeg_sources/libaom && \
        git clone https://aomedia.googlesource.com/aom && \
        cmake ./aom && \
        make && \
        sudo make install

		# Warning: libaom does not yet appear to have a stable API
		
        #cd ~/ffmpeg_sources && \
        #git -C aom pull 2> /dev/null || git clone --depth 1 https://aomedia.googlesource.com/aom && \
        #mkdir aom_build && \
        #cd aom_build && \
        #PATH="$HOME/bin:$PATH" cmake -G "Unix Makefiles" -DCMAKE_INSTALL_PREFIX="$HOME/ffmpeg_build" -DENABLE_SHARED=off -DENABLE_NASM=on ../aom && \
        #PATH="$HOME/bin:$PATH" make && \
        #make install
		
        cd ~/ffmpeg_sources && \
        wget -O ffmpeg-4.3.1.tar.bz2 https://ffmpeg.org/releases/ffmpeg-4.3.1.tar.bz2 && \
        tar xjvf ffmpeg-4.3.1.tar.bz2 && \
        cd ~/ffmpeg_sources/ffmpeg-4.3.1 && \
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
           --enable-libaom \
           --enable-nonfree && \
           PATH="$HOME/bin:$PATH" make && \
           make install && \
           hash -r
           cp -R ~/bin /usr/local
           echo $'\n'"$(tput setaf 2) FFmpeg VAAPI+CUDA installed.$(tput sgr 0)"$'\n'
    fi
	
	if [[ ${choices[6]} ]]; then
        #Option 7 selected
		echo $'\n'"$(tput setaf 2) ...setting up FFmpeg VAAPI$(tput sgr 0)"$'\n'
        git clone https://github.com/FFmpeg/nv-codec-headers.git && \
        cd ~/FFmpegUI/nv-codec-headers && \
        make -j4 && \
        sudo make install
		
        rm -R ~/FFmpegUI/nv-codec-headers
        mkdir -p ~/ffmpeg_sources ~/bin
        cd ~/ffmpeg_sources && \
        apt-get -y install nasm yasm
		
        # Install libaom from source.
        mkdir -p ~/ffmpeg_sources/libaom && \
        cd ~/ffmpeg_sources/libaom && \
        git clone https://aomedia.googlesource.com/aom && \
        cmake ./aom && \
        make && \
        sudo make install

		# Warning: libaom does not yet appear to have a stable API
		
        #cd ~/ffmpeg_sources && \
        #git -C aom pull 2> /dev/null || git clone --depth 1 https://aomedia.googlesource.com/aom && \
        #mkdir aom_build && \
        #cd aom_build && \
        #PATH="$HOME/bin:$PATH" cmake -G "Unix Makefiles" -DCMAKE_INSTALL_PREFIX="$HOME/ffmpeg_build" -DENABLE_SHARED=off -DENABLE_NASM=on ../aom && \
        #PATH="$HOME/bin:$PATH" make && \
        #make install
		
        cd ~/ffmpeg_sources && \
        wget -O ffmpeg-4.3.1.tar.bz2 https://ffmpeg.org/releases/ffmpeg-4.3.1.tar.bz2 && \
        tar xjvf ffmpeg-4.3.1.tar.bz2 && \
        cd ~/ffmpeg_sources/ffmpeg-4.3.1 && \
        PATH="$HOME/bin:$PATH" PKG_CONFIG_PATH="$HOME/ffmpeg_build/lib/pkgconfig" ./configure \
           --prefix="$HOME/ffmpeg_build" \
           --pkg-config-flags="--static" \
           --extra-cflags="-I$HOME/ffmpeg_build/include" \
           --extra-ldflags="-L$HOME/ffmpeg_build/lib" \
           --extra-libs="-lpthread -lm" \
           --bindir="$HOME/bin" \
           --enable-gpl \
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
           --enable-libaom \
           --enable-nonfree && \
           PATH="$HOME/bin:$PATH" make && \
           make install && \
           hash -r
           cp -R ~/bin /usr/local
        echo $'\n'"$(tput setaf 2) FFmpeg VAAPI installed.$(tput sgr 0)"$'\n'
    fi
	
    if [[ ${choices[7]} ]]; then
        #Option 8 selected
		echo $'\n'"$(tput setaf 2) ...setting up FFmpegUI and Apache2$(tput sgr 0)"$'\n'
		apt-get -y --force-yes install software-properties-common
               add-apt-repository ppa:heyarje/makemkv-beta -y
               apt update -y
               apt -y install makemkv-bin makemkv-oss
               sudo npm i cloudcmd pm2 -g
               sudo pm2 start `which cloudcmd`
        	export LANG=C.UTF-8
		apt-add-repository ppa:ondrej/php -y --force-yes
		sudo apt update -qq && sudo apt dist-upgrade -qq && 
		sudo apt install -y --force-yes php-bcmath php-zip php-dev php-xml php-pear apache2 libapache2-mod-php php-pear rar
	        sed -i '2iextension=rar.so' /etc/php/*.*/apache2/php.ini
		mkdir ~/FFmpegUI/FFmpeg_UI/media
		mkdir ~/FFmpegUI/FFmpeg_UI/media/input
		mkdir ~/FFmpegUI/FFmpeg_UI/media/tmp
		mkdir ~/FFmpegUI/FFmpeg_UI/media/output
		mkdir ~/FFmpegUI/FFmpeg_UI/media/backup		
		cp -R ~/FFmpegUI/FFmpeg_UI /var/www/html/
                sudo usermod -a -G video root
                sudo usermod -a -G postfix root
                sudo usermod -a -G postfix www-data
		sudo usermod -a -G cdrom www-data
		sudo usermod -a -G video www-data
		sudo usermod -a -G root www-data
		sudo usermod -a -G sudo www-data
		chown -R www-data /var/www/html/FFmpeg_UI
        echo $'\n'"$(tput setaf 2) FFmpegUI and Apache2 installed.$(tput sgr 0)"$'\n'
	chmod -R 777 /var/www/html/FFmpeg_UI/media	
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

	if [[ ${choices[8]} ]]; then
        #Option 9 selected
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

if [ $distribution = "ubuntu64" ]; then
      echo $"$hr"
      echo $"$(tput setaf 2) FFmpegUI v.2.0 installation (VAAPI + CUDA)$(tput sgr 0)"
      echo $' https://github.com/Sub-7/FFmpegUI'$'\n'$hr
      echo  " Distribution: $(tput setaf 2)$OS $ARCH $VERSION   $(tput sgr 0)"
    else
      echo  " $(tput setaf 1) Distribution not supported, see install.log in FFmpegUI folder for errors.$(tput sgr 0)"
    fi
    
    while read -r line
    do
    name="$line"
	echo " MB  : $(tput setaf 2)${name:14}$(tput sgr 0)"
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
	  echo " Nvidia Driver:$(tput setaf 2)${NV_VERSION:16}$(tput sgr 0)"
      else 
      echo  " Nvidia driver:$(tput setaf 1)  not installed$(tput sgr 0)"
    fi
	
	if [ -e "/usr/local/cuda/version.txt" ]; then
      CUDA_V=$(cat /usr/local/cuda/version.txt)	
      echo  " CUDA Version:$(tput setaf 2) ${CUDA_V:13}$(tput sgr 0)"
      else 
      echo  " CUDA:$(tput setaf 1)           not installed$(tput sgr 0)"
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
while MENU && read -e -p " Select options (ENTER when done): " -n1 SELECTION && [[ -n "$SELECTION" ]]; do
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
